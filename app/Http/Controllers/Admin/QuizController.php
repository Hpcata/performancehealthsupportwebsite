<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        // Get all questions grouped by form_slug
        $questions = QuizQuestion::orderBy('question_index')->get()->groupBy('form_slug');
        return view('backend.pages.quiz.form', compact('questions'));
    }

    public function create()
    {
        return view('backend.pages.quiz.form');
    }

    public function store(Request $request)
    {
        try {
            // First delete all existing questions
            QuizQuestion::truncate();
            // dd($request->all());
            $questions = $request->input('questions', []);
            // dd($questions);
            foreach ($questions as $question) {
                QuizQuestion::create([
                    'form_slug' => $question['form_slug'],
                    'question_index' => $question['question_index'],
                    'question_text' => $question['question_text'],
                    'options' => $question['options'] ?? null,
                    'correct_answer' => $question['correct_answer'] ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Quiz questions saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving quiz questions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $question = QuizQuestion::findOrFail($id);
        return view('backend.pages.quiz.show', compact('question'));
    }

    public function edit($id)
    {
        $question = QuizQuestion::findOrFail($id);
        return view('backend.pages.quiz.form', compact('question'));
    }

    public function update(Request $request, $id)
    {
        try {
            $question = QuizQuestion::findOrFail($id);
            
            $question->update([
                'form_slug' => $request->input('form_slug'),
                'question_index' => $request->input('question_index'),
                'question_text' => $request->input('question_text'),
                'options' => $request->input('options'),
                'correct_answer' => $request->input('correct_answer')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz question updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating quiz question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $question = QuizQuestion::findOrFail($id);
            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quiz question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting quiz question: ' . $e->getMessage()
            ], 500);
        }
    }
} 