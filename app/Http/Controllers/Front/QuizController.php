<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityTracker;
use App\Models\TrackingType;
use App\Models\Tracking;

class QuizController extends Controller
{
    public function startQuiz(Request $request)
    {
        try {
            $quiz = Quiz::create([
                'user_id' => null,
                'ip_address' => $request->ip(),
                'status' => 'in_progress',
                'is_completed' => false,
                'started_at' => now()
            ]);

            $click = ActivityTracker::click('quiz_button_click', null);

            // Log in trackings with click reference
            ActivityTracker::log(TrackingType::QUIZ_BUTTON_CLICK, null, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
            ]);

            return response()->json([
                'success' => true,
                'quiz_id' => $quiz->id
            ]);
        } catch (\Exception $e) {
            Log::error('Quiz starting error. ' .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error starting quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveStep(Request $request)
    {
        // ① validate
        $validator = Validator::make($request->all(), [
            'quiz_id'  => 'required|exists:quizzes,id',
            'step'     => 'required|integer|min:1|max:9',
            'stepData' => 'required|string'          // raw JSON
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
        
            // ② decode JSON -> array
            $stepData = json_decode($request->stepData, true);
            if (!is_array($stepData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'stepData is not valid JSON'
                ], 422);
            }

            // ③ find quiz
            $quiz = Quiz::findOrFail($request->quiz_id);

            if($request->step == 1) {
                $click = ActivityTracker::click('quiz_started', null);

                // Log in trackings with click reference
                ActivityTracker::log(TrackingType::QUIZ_STARTED, null, [
                    'user_click_id' => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'quiz_id' => $quiz->id,
                ]);
            }

            DB::transaction(function () use ($stepData, $quiz, $request) {
                foreach ($stepData as $formSlug => $questions) {
                    $index = 1;
                    foreach ($questions as $questionText => $answers) {
                        $form_slug = null;
                        if($formSlug == 'nutrition-form') {
                            $form_slug = 'nutrition';
                        }else if($formSlug == 'sports-form') {
                            $form_slug = 'sports';
                        }else if($formSlug == 'supplement-form') {
                            $form_slug = 'supplements';
                        }
                        // Fetch the QuizQuestion for this form_slug and question_text
                        $quizQuestion = \App\Models\QuizQuestion::where('form_slug', $form_slug)
                            ->where('question_text', $questionText)
                            ->first();
                        $isCorrect = false;
                        $isUnsure = false;
                        $selectedValue = null;
                        if ($quizQuestion) {
                            // Assume $answers is an array or value, get the selected value
                            if (is_array($answers)) {
                                $selectedValue = $answers['value'] ?? (array_values($answers)[0] ?? null);
                            } else {
                                $selectedValue = $answers;
                            }
                            // Check for 'unsure' (string or value)
                            if (is_string($selectedValue) && strtolower($selectedValue) === 'unsure') {
                                $isUnsure = true;
                            } elseif ($selectedValue === 'unsure') {
                                $isUnsure = true;
                            } else {
                                // correct_answer is an array, value 1 is correct
                                $correctAnswers = $quizQuestion->correct_answer;
                                if (is_array($correctAnswers)) {
                                    // If the selected value matches a key with value 1, it's correct
                                    foreach ($correctAnswers as $optionKey => $isCorrectVal) {
                                        if ($selectedValue == $optionKey && $isCorrectVal == 1) {
                                            $isCorrect = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    
                        \App\Models\QuizAnswer::create([
                            'quiz_id'        => $quiz->id,
                            'form_slug'      => $formSlug,
                            'question'       => $questionText,
                            'question_index' => $quizQuestion ? $quizQuestion->question_index : null,
                            'step'           => $request->step,
                            'answer'         => json_encode($answers)
                        ]);
                        // Enhanced: Calculate percentage correct for multi-option answers using QuizQuestion->options
                        $optionCorrectCount = 0;
                        $optionTotalCount = 0;
                        $optionUnsureCount = 0;
                        $questionOptions = $quizQuestion ? $quizQuestion->options : [];
                        if (is_array($answers)) {
                            foreach ($answers as $optionKey => $optionData) {
                                $optionTotalCount++;
                                $isCorrect = false;
                                // 1. Check for 'correct' key in answer structure
                                if (isset($optionData['correct']) && $optionData['correct'] == 1) {
                                    $isCorrect = true;
                                }
                                // 2. Check against QuizQuestion->options structure
                                $selectedLabel = isset($optionData['option']) ? $optionData['option'] : null;
                                $correctForOption = isset($questionOptions[$optionKey]) ? $questionOptions[$optionKey] : null;
                                if (is_array($correctForOption) && $selectedLabel !== null) {
                                    if (isset($correctForOption[$selectedLabel]) && $correctForOption[$selectedLabel] == 1) {
                                        $isCorrect = true;
                                    }
                                }
                                if ($isCorrect) {
                                    $optionCorrectCount++;
                                }
                                // Count unsure
                                if ($selectedLabel !== null && strtolower($selectedLabel) === 'unsure') {
                                    $optionUnsureCount++;
                                }
                            }
                        }
                        $questionPercentCorrect = $optionTotalCount > 0 ? round(($optionCorrectCount / $optionTotalCount) * 100, 2) : 0;
                        $questionPercentCorrect = number_format($questionPercentCorrect, 2, '.', '');
                        // Determine if any option is unsure for this question
                        $correctAnswerUnsure = 0;
                        if ($optionUnsureCount > 0) {
                            $correctAnswerUnsure = 'unsure';
                        } elseif ($optionTotalCount > 0 && $optionUnsureCount === 0) {
                            $correctAnswerUnsure = 0;
                        }
                        // Log tracking for each question with percentage correct
                        $click = ActivityTracker::click('quiz_question_answer', null);

                        \App\Services\ActivityTracker::log(\App\Models\TrackingType::QUIZ_QUESTION_ANSWER, null, [
                            'user_click_id' => $click->id,
                            'section_element_id' => $click->section_element_id,
                            'quiz_id' => $quiz->id,
                            'step' => $request->step,
                            'question_id' => $quizQuestion ? $quizQuestion->question_index : null,
                            'question_text' => $questionText,
                            'form_slug' => $formSlug,
                            'selected' => $selectedValue,
                            'correct_answer_percent' => $questionPercentCorrect,
                            'option_total' => $optionTotalCount,
                            'option_correct' => $optionCorrectCount,
                            'option_unsure' => $optionUnsureCount,
                            'correct_answer_unsure' => $correctAnswerUnsure,
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Step saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Quiz save error. ' .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error save quiz: ' . $e->getMessage()
            ], 500);
        }
    }
   
    public function completeQuiz(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required|exists:quizzes,id',
                'totalAnswerCounts' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $quiz = Quiz::findOrFail($request->quiz_id);
            
            $nutritionScore  = $request->totalAnswerCounts['nutrition-form'] ?? 0;
            $sportsScore     = $request->totalAnswerCounts['sports-form'] ?? 0;
            $supplementScore = $request->totalAnswerCounts['supplement-form'] ?? 0;

            // Generate feedback based on score ranges
            $nutritionFeedback  = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');
            $sportsFeedback     = $this->getFeedbackMessage($sportsScore, 'sports-form');
            $supplementFeedback = $this->getFeedbackMessage($supplementScore, 'supplement-form');

            // Update quiz status
            $quiz->update([
                'user_id' => $request->user_id,
                'status' => 'completed',
                'is_completed' => true,
                'nutrition_score' => $nutritionScore,
                'nutrition_feedback' => $nutritionFeedback,
                'sports_score' => $sportsScore,
                'sports_feedback' => $sportsFeedback,
                'supplements_score' => $supplementScore,
                'supplements_feedback' => $supplementFeedback,
                'completed_at' => now()
            ]);

            $click = ActivityTracker::click('quiz_submit_button_click', $request->user_id);

            // Log in trackings with click reference
            ActivityTracker::log(TrackingType::QUIZ_COMPLETED, $request->user_id, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'quiz_id' => $quiz->id,
            ]);

            Tracking::where('details->quiz_id', $quiz->id)
                ->update(['user_id' => $request->user_id]);
                
            try {
                $user = User::find($request->user_id);
                // $adminEmail = 'kerry@performancehealthsupport.com'; // Set admin email address
                $adminEmail = 'kartikvadhaiya6656@gmail.com'; // Set admin email address
                Mail::to($adminEmail)->send(new \App\Mail\QuizSubmittedMail($user, $quiz));

                Mail::to($user->email)->send(new \App\Mail\FreeTestResultMail($user, $quiz));

            } catch (\Exception $e) {
                Log::error('Quiz completed mail send error. ' .$e->getMessage());
            }
 
            // Here you can add code to send email notifications, etc.

            return response()->json([
                'success' => true,
                'message' => 'Quiz completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Quiz completed error. ' .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error completing quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getFeedbackMessage($score, $category)
    {
        switch ($category) {
            case 'nutrition-form': // Score out of 35
                if ($score <= 19) return 'Needs work';
                if ($score <= 25) return 'Pretty ordinary';
                if ($score <= 31) return 'Not bad';
                // if ($score <= 35) return 'Good';
                return 'Good';

            case 'sports-form': // Score out of 9
                if ($score <= 4) return 'Untapped potential';
                if ($score <= 8) return 'Much to learn';
                if ($score <= 11) return 'Ok';
                return 'Good start';

            case 'supplement-form': // Score out of 6
                if ($score <= 2) return 'Likely at risk';
                if ($score <= 3) return 'Pretty ordinary';
                if ($score <= 4) return 'Decent';
                return 'Nice';

            default:
                return 'No feedback available';
        }
    }

    public function abandonQuiz(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required|exists:quizzes,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $quiz = Quiz::findOrFail($request->quiz_id);
            
            // Update quiz status
            $quiz->update([
                'status' => 'abandoned',
                'is_completed' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz abandoned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error abandoning quiz: ' . $e->getMessage()
            ], 500);
        }
    }
} 