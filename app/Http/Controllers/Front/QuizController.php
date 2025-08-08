<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\FreeTestResultMail;
use App\Mail\QuizSubmittedMail;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\Tracking;
use App\Models\TrackingType;
use App\Models\User;
use App\Services\ActivityTracker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Start a new quiz session for tracking user interactions
     *
     * Creates a new quiz record with user's IP address and tracks the quiz button click
     * for analytics purposes. Returns quiz ID for frontend session management.
     *
     * @param Request $request The incoming HTTP request
     * @return JsonResponse JSON response with quiz status and ID
     */
    public function startQuiz(Request $request)
    {
        try {
            // Create new quiz session record
            $quiz = Quiz::create([
                'user_id'      => null, // Anonymous user for now
                'ip_address'   => $request->ip(),
                'status'       => 'in_progress',
                'is_completed' => false,
                'started_at'   => now(),
            ]);

            // Track quiz button click for analytics
            $click = ActivityTracker::click('quiz_button_click', null);

            // Log detailed tracking information
            ActivityTracker::log(TrackingType::QUIZ_BUTTON_CLICK, null, [
                'user_click_id'      => $click->id,
                'section_element_id' => $click->section_element_id,
                'quiz_id'            => $quiz->id, // Link tracking to quiz session
            ]);

            return response()->json([
                'success' => true,
                'quiz_id' => $quiz->id,
                'message' => 'Quiz started successfully',
            ]);

        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Quiz starting error: ' . $e->getMessage(), [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp'  => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to start quiz. Please try again.',
            ], 500);
        }
    }

    public function saveStep(Request $request)
    {
        // ① validate
        $validator = Validator::make($request->all(), [
            'quiz_id'  => 'required|exists:quizzes,id',
            'step'     => 'required|integer|min:1|max:10',
            'stepData' => 'required|string', // raw JSON
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {

            // ② decode JSON -> array
            $stepData = json_decode($request->stepData, true);
            if (! is_array($stepData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'stepData is not valid JSON',
                ], 422);
            }

            // ③ find quiz
            $quiz = Quiz::findOrFail($request->quiz_id);

            if ($request->step == 1) {
                $click = ActivityTracker::click('quiz_started', null);

                // Log in trackings with click reference
                ActivityTracker::log(TrackingType::QUIZ_STARTED, null, [
                    'user_click_id'      => $click->id,
                    'section_element_id' => $click->section_element_id,
                    'quiz_id'            => $quiz->id,
                ]);
            }

            DB::transaction(function () use ($stepData, $quiz, $request) {
                foreach ($stepData as $formSlug => $questions) {
                    foreach ($questions as $questionText => $answers) {
                        $form_slug = match ($formSlug) {
                            'nutrition-form'  => 'nutrition',
                            'sports-form'     => 'sports',
                            'supplement-form' => 'supplements',
                            default           => null
                        };

                        // Find quiz question with flexible matching
                        $quizQuestion = $this->findQuizQuestion($form_slug, $questionText);

                        // Check if an answer already exists for this quiz, step, and question
                        $existingAnswer = QuizAnswer::where('quiz_id', $quiz->id)
                            ->where('step', $request->step)
                            ->where('question', $questionText)
                            ->first();

                        // Determine question index
                        $questionIndex = $quizQuestion ? $quizQuestion->question_index : 0;

                        if (! $quizQuestion) {
                            // Log the missing question for debugging
                            Log::warning('Quiz question not found', [
                                'form_slug'      => $form_slug,
                                'question_text'  => $questionText,
                                'quiz_id'        => $quiz->id,
                                'step'           => $request->step,
                                'question_index' => $questionIndex,
                            ]);

                            if ($existingAnswer) {
                                // Update existing answer
                                $existingAnswer->update([
                                    'answer'         => json_encode($answers),
                                    'question_index' => $questionIndex,
                                ]);
                            } else {
                                // Create new answer record with determined question_index
                                QuizAnswer::create([
                                    'quiz_id'        => $quiz->id,
                                    'form_slug'      => $formSlug,
                                    'question'       => $questionText,
                                    'question_index' => $questionIndex,
                                    'step'           => $request->step,
                                    'answer'         => json_encode($answers),
                                ]);
                            }
                            continue;
                        }

                        $selectedValue = null;

                        // Process answers for new structure
                        if (is_array($answers)) {
                            $selectedValue = $answers['value'] ?? (array_values($answers)[0] ?? null);
                        } else {
                            $selectedValue = $answers;
                        }

                        if ($existingAnswer) {
                            // Update existing answer
                            $existingAnswer->update([
                                'form_slug'      => $formSlug,
                                'question_index' => $questionIndex,
                                'answer'         => json_encode($answers),
                            ]);
                        } else {
                            // Create new quiz answer record
                            QuizAnswer::create([
                                'quiz_id'        => $quiz->id,
                                'form_slug'      => $formSlug,
                                'question'       => $questionText,
                                'question_index' => $questionIndex,
                                'step'           => $request->step,
                                'answer'         => json_encode($answers),
                            ]);
                        }

                        // Calculate answer statistics for new structure
                        $answerStats = $this->calculateAnswerStatistics($answers, $quizQuestion);

                        // Track activity
                        $this->trackQuizActivity($quiz, $request, $quizQuestion, $questionText, $formSlug, $selectedValue, $answerStats);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Step saved successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Quiz save error. ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error save quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function completeQuiz(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id'           => 'required|exists:quizzes,id',
                'totalAnswerCounts' => 'required',
                'user_id'           => 'nullable', // Make user_id optional
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $quiz = Quiz::findOrFail($request->quiz_id);

            // Use the nutrition score calculated and sent from the frontend
            $nutritionScore = $request->totalAnswerCounts['nutrition-form'] ?? 0;

            // Generate feedback based on score ranges
            $nutritionFeedback = $this->getFeedbackMessage($nutritionScore, 'nutrition-form');

            // Update quiz status
            $quiz->update([
                'user_id'              => $request->user_id, // Can be null for anonymous users
                'status'               => 'completed',
                'is_completed'         => true,
                'nutrition_score'      => $nutritionScore,
                'nutrition_feedback'   => $nutritionFeedback,
                'completed_at'         => now(),
            ]);

            $click = ActivityTracker::click('quiz_submit_button_click', $request->user_id);

            // Log in trackings with click reference
            ActivityTracker::log(TrackingType::QUIZ_COMPLETED, $request->user_id, [
                'user_click_id'      => $click->id,
                'section_element_id' => $click->section_element_id,
                'quiz_id'            => $quiz->id,
            ]);

            // Only update tracking if user_id is provided
            if ($request->user_id) {
                Tracking::where('details->quiz_id', $quiz->id)
                    ->update(['user_id' => $request->user_id]);
            }

            // Only send emails if user_id is provided
            if ($request->user_id) {
                try {
                    $user       = User::find($request->user_id);
                    $adminEmail = config('constant.admin_email'); // Set admin email address
                    // $adminEmail = 'kartikvadhaiya6656@gmail.com'; // Set admin email address
                    Mail::to($adminEmail)->send(new QuizSubmittedMail($user, $quiz));

                    Mail::to($user->email)->send(new FreeTestResultMail($user, $quiz));

                } catch (\Exception $e) {
                    Log::error('Quiz completed mail send error. ' . $e->getMessage());
                }
            }

            // Here you can add code to send email notifications, etc.

            return response()->json([
                'success' => true,
                'message' => 'Quiz completed successfully',
                'nutrition_score' => $nutritionScore,
            ]);
        } catch (\Exception $e) {
            Log::error('Quiz completed error. ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error completing quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getFeedbackMessage($score, $category)
    {
        switch ($category) {
            case 'nutrition-form': // Score out of 35
                if ($score <= 19) {
                    return 'Needs work';
                }

                if ($score <= 25) {
                    return 'Pretty ordinary';
                }

                if ($score <= 31) {
                    return 'Not bad';
                }

                return 'Good';

            default:
                return 'No feedback available';
        }
    }

    public function abandonQuiz(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required|exists:quizzes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $quiz = Quiz::findOrFail($request->quiz_id);

            // Update quiz status
            $quiz->update([
                'status'       => 'abandoned',
                'is_completed' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz abandoned successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error abandoning quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Find quiz question with flexible text matching
     */
    private function findQuizQuestion(string $formSlug, string $questionText): ?QuizQuestion
    {
        // First try exact match
        $quizQuestion = QuizQuestion::where('form_slug', $formSlug)
            ->where('question_text', $questionText)
            ->first();

        if ($quizQuestion) {
            return $quizQuestion;
        }

        // Try partial matching for common variations
        $normalizedQuestion = $this->normalizeQuestionText($questionText);

        $quizQuestion = QuizQuestion::where('form_slug', $formSlug)
            ->get()
            ->first(function ($question) use ($normalizedQuestion) {
                $normalizedDBQuestion = $this->normalizeQuestionText($question->question_text);
                $isSimilar            = $this->questionsAreSimilar($normalizedQuestion, $normalizedDBQuestion);
                return $isSimilar;
            });

        if (! $quizQuestion) {
            Log::warning('No quiz question found for text', [
                'form_slug'       => $formSlug,
                'question_text'   => $questionText,
                'normalized_text' => $normalizedQuestion,
            ]);
        }

        return $quizQuestion;
    }

    /**
     * Normalize question text for comparison
     */
    private function normalizeQuestionText(string $text): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $text)));
    }

    /**
     * Check if two questions are similar enough to be considered the same
     */
    private function questionsAreSimilar(string $text1, string $text2): bool
    {
        // First try exact match after normalization
        if ($text1 === $text2) {
            return true;
        }

        // Check for key nutrition terms
        $nutritionTerms = ['carbohydrate', 'protein', 'fat', 'foods', 'high', 'unsure', 'decisions', 'percentage', 'calories', 'reason', 'nutrients'];

        $text1Words = explode(' ', $text1);
        $text2Words = explode(' ', $text2);

        $commonWords    = array_intersect($text1Words, $text2Words);
        $nutritionWords = array_intersect($commonWords, $nutritionTerms);

        // If they share nutrition-related words, they're likely the same question
        if (count($nutritionWords) >= 2) {
            return true;
        }

        // For step 7 questions, check for specific keywords
        $step7Keywords = [
            'decisions'  => ['decisions', 'day', 'eat'],
            'percentage' => ['percentage', 'people', 'struggle', 'healthy', 'food', 'choices'],
            'calories'   => ['calories', 'average', 'person', 'consume', 'day'],
            'reason'     => ['reason', 'people', 'give', 'nutrition', 'goals'],
            'nutrients'  => ['nutrients', 'human', 'body', 'function', 'properly'],
        ];

        foreach ($step7Keywords as $key => $keywords) {
            $text1HasKeywords = count(array_intersect($text1Words, $keywords)) >= 2;
            $text2HasKeywords = count(array_intersect($text2Words, $keywords)) >= 2;

            if ($text1HasKeywords && $text2HasKeywords) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate answer statistics for new structure
     */
    private function calculateAnswerStatistics(array $answers, QuizQuestion $quizQuestion): array
    {
        $optionCorrectCount = 0;
        $optionTotalCount   = 0;
        $optionUnsureCount  = 0;
        $questionOptions    = $quizQuestion->options ?? [];

        if (is_array($answers)) {
            foreach ($answers as $optionKey => $optionData) {
                $optionTotalCount++;
                $isCorrect = false;

                if (isset($optionData['correct']) && $optionData['correct'] == 1) {
                    $isCorrect = true;
                }

                $selectedLabel    = $optionData['option'] ?? null;
                $correctForOption = $questionOptions[$optionKey] ?? null;

                // Handle new structure with High/Unsure options
                if (is_array($correctForOption) && $selectedLabel !== null) {
                    if (isset($correctForOption[$selectedLabel]) && $correctForOption[$selectedLabel] == "1") {
                        $isCorrect = true;
                    }
                } else if (is_string($correctForOption) && $selectedLabel !== null) {
                    // Handle multiple choice questions
                    if ($correctForOption == "1") {
                        $isCorrect = true;
                    }
                }

                if ($isCorrect) {
                    $optionCorrectCount++;
                }

                if ($selectedLabel !== null && strtolower($selectedLabel) === 'unsure') {
                    $optionUnsureCount++;
                }
            }
        }

        $questionPercentCorrect = $optionTotalCount > 0 ? round(($optionCorrectCount / $optionTotalCount) * 100, 2) : 0;
        $questionPercentCorrect = number_format($questionPercentCorrect, 2, '.', '');

        $unsureAnswerPercent = $optionTotalCount > 0 ? round(($optionUnsureCount / $optionTotalCount) * 100, 2) : 0;
        $unsureAnswerPercent = number_format($unsureAnswerPercent, 2, '.', '');

        $correctAnswerUnsure = 0;
        if ($optionUnsureCount > 0) {
            $correctAnswerUnsure = 'unsure';
        } elseif ($optionTotalCount > 0 && $optionUnsureCount === 0) {
            $correctAnswerUnsure = 0;
        }

        return [
            'correct_count'         => $optionCorrectCount,
            'total_count'           => $optionTotalCount,
            'unsure_count'          => $optionUnsureCount,
            'percent_correct'       => $questionPercentCorrect,
            'percent_unsure'        => $unsureAnswerPercent,
            'correct_answer_unsure' => $correctAnswerUnsure,
        ];
    }

    /**
     * Get nutrition score for a specific quiz
     *
     * @param Request $request The incoming HTTP request
     * @return JsonResponse JSON response with nutrition score and calculated values
     */
    public function getNutritionScore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required|exists:quizzes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $quiz = Quiz::findOrFail($request->quiz_id);

            if (!$quiz->nutrition_score) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nutrition score not found for this quiz',
                ], 404);
            }

            $nutritionScore = $quiz->nutrition_score;
            $nutritionMaxTotal = 35; // Maximum possible score
            $nutritionPercentage = max(0, ($nutritionScore / $nutritionMaxTotal) * 100);

            // Calculate arrow rotation (5.14285714 degrees per point)
            $nutritionDegree = 5.14285714;
            $nutritionTotalDegree = max(0, $nutritionScore * $nutritionDegree);

            // Ensure the rotation stays within the gauge limits (0-180 degrees)
            // The gauge appears to be a semi-circle, so we limit to 180 degrees
            $arrowRotation = min(180, max(0, $nutritionTotalDegree));

            return response()->json([
                'success' => true,
                'nutrition_score' => $nutritionScore,
                'nutrition_percentage' => round($nutritionPercentage, 1),
                'arrow_rotation' => $arrowRotation,
                'feedback' => $quiz->nutrition_feedback,
            ]);

        } catch (\Exception $e) {
            Log::error('Get nutrition score error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving nutrition score: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Track quiz activity for analytics
     */
    private function trackQuizActivity(Quiz $quiz, Request $request, ?QuizQuestion $quizQuestion, string $questionText, string $formSlug, $selectedValue, array $answerStats): void
    {
        $click = ActivityTracker::click('quiz_question_answer', null);

        ActivityTracker::log(TrackingType::QUIZ_QUESTION_ANSWER, null, [
            'user_click_id'          => $click->id,
            'section_element_id'     => $click->section_element_id,
            'quiz_id'                => $quiz->id,
            'step'                   => $request->step,
            'question_id'            => $quizQuestion?->question_index ?? 0,
            'question_text'          => $questionText,
            'form_slug'              => $formSlug,
            'selected'               => $selectedValue,
            'correct_answer_percent' => $answerStats['percent_correct'],
            'unsure_answer_percent'  => $answerStats['percent_unsure'],
            'option_total'           => $answerStats['total_count'],
            'option_correct'         => $answerStats['correct_count'],
            'option_unsure'          => $answerStats['unsure_count'],
            'correct_answer_unsure'  => $answerStats['correct_answer_unsure'],
        ]);
    }
}
