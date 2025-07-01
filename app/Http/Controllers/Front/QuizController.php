<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityTracker;
use App\Models\TrackingType;
class QuizController extends Controller
{
    public function startQuiz(Request $request)
    {
        // dd($request->all());
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
            dd($e->getMessage());
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

        // ④ save every question in this step
        DB::transaction(function () use ($stepData, $quiz, $request) {

            foreach ($stepData as $formSlug => $questions) {            // nutrition‑form, sports‑form …
                $index = 1;                                             // question_index within this step

                foreach ($questions as $questionText => $answers) {

                    QuizAnswer::create([
                        'quiz_id'        => $quiz->id,
                        'form_slug'      => $formSlug,
                        'question'       => $questionText,
                        'question_index' => $index++,
                        'step'           => $request->step,
                        'answer'         => json_encode($answers)       // ⇐ store full object
                    ]);
                }
            }
        });

        if($request->step == 1) {
            $click = ActivityTracker::click('quiz_started', null);

            // Log in trackings with click reference
            ActivityTracker::log(TrackingType::QUIZ_STARTED, null, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'quiz_id' => $quiz->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Step saved successfully'
        ]);
    }

    // public function saveStep(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'quiz_id' => 'required|exists:quizzes,id',
    //             'step' => 'required|integer|min:1|max:9',
    //             'answers' => 'required|array'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Validation error',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $quiz = Quiz::findOrFail($request->quiz_id);
    //         dd($request->all());
    //         QuizAnswer::create([
    //             'quiz_id' => $quiz->id,
    //             'form_slug' => $answer['form_slug'],
    //             'question' => $answer['question'],
    //             'question_index' => $answer['question_index'],
    //             'step' => $request->step,
    //             'answer' => $answer['answer']
    //         ]);

    //         // Save each answer
    //         // foreach ($request->answers as $answer) {
    //         //     QuizAnswer::create([
    //         //         'quiz_id' => $quiz->id,
    //         //         'form_slug' => $answer['form_slug'],
    //         //         'question' => $answer['question'],
    //         //         'question_index' => $answer['question_index'],
    //         //         'step' => $request->step,
    //         //         'answer' => $answer['answer']
    //         //     ]);
    //         // }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Step saved successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error saving step: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

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