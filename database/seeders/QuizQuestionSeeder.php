<?php
namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing questions
        QuizQuestion::truncate();

        // Nutrition questions based on modal.blade.php
        $nutritionQuestions = [
            [
                'form_slug'      => 'nutrition',
                'question_index' => 1,
                'question_text'  => 'Select the foods that are high in carbohydrate.',
                'options'        => [
                    "Cream"        => ["High" => "0", "Unsure" => "0"],
                    "Avocado"      => ["High" => "0", "Unsure" => "0"],
                    "Chicken"      => ["High" => "0", "Unsure" => "0"],
                    "Crumpets"     => ["High" => "0", "Unsure" => "0"],
                    "Weet-bix"     => ["High" => "0", "Unsure" => "0"],
                    "Baked beans"  => ["High" => "0", "Unsure" => "0"],
                    "Grain bread"  => ["High" => "0", "Unsure" => "0"],
                    "Fruit yogurt" => ["High" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Cream"   => ["value" => "1", "option" => "High"],
                    "Avocado" => ["value" => "1", "option" => "High"],
                    "Chicken" => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 2,
                'question_text'  => 'Select the foods that are high in protein.',
                'options'        => [
                    "Salmon"            => ["High" => "0", "Unsure" => "0"],
                    "Baked beans"       => ["High" => "0", "Unsure" => "0"],
                    "Fruit"             => ["High" => "0", "Unsure" => "0"],
                    "Hummus"            => ["High" => "0", "Unsure" => "0"],
                    "Cornflakes cereal" => ["High" => "0", "Unsure" => "0"],
                    "Almonds"           => ["High" => "0", "Unsure" => "0"],
                    "Flavoured milk"    => ["High" => "0", "Unsure" => "0"],
                    "Ice cream"         => ["High" => "0", "Unsure" => "0"],
                    "Almond/oat milk"   => ["High" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Salmon"      => ["value" => "1", "option" => "High"],
                    "Baked beans" => ["value" => "1", "option" => "High"],
                    "Fruit"       => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 3,
                'question_text'  => 'Select the foods that are high in fat.',
                'options'        => [
                    "Avocado"              => ["High" => "0", "Unsure" => "0"],
                    "Baked beans"          => ["High" => "0", "Unsure" => "0"],
                    "Cottage cheese"       => ["High" => "0", "Unsure" => "0"],
                    "Peanut butter"        => ["High" => "0", "Unsure" => "0"],
                    "Crumpets"             => ["High" => "0", "Unsure" => "0"],
                    "Cheddar/Tasty cheese" => ["High" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Avocado"              => ["value" => "1", "option" => "High"],
                    "Baked beans"          => ["value" => "1", "option" => "High"],
                    "Cottage cheese"       => ["value" => "1", "option" => "High"],
                    "Peanut butter"        => ["value" => "1", "option" => "High"],
                    "Crumpets"             => ["value" => "1", "option" => "High"],
                    "Cheddar/Tasty cheese" => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 4,
                'question_text'  => 'Select the foods that are high in healthy fats.',
                'options'        => [
                    "Butter"                 => ["High" => "0", "Unsure" => "0"],
                    "Extra virgin olive oil" => ["High" => "0", "Unsure" => "0"],
                    "Whole milk"             => ["High" => "0", "Unsure" => "0"],
                    "Potato chips"           => ["High" => "0", "Unsure" => "0"],
                    "Salmon"                 => ["High" => "0", "Unsure" => "0"],
                    "Dark chocolate"         => ["High" => "0", "Unsure" => "0"],
                    "Macadamia nuts"         => ["High" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Butter"                 => ["value" => "1", "option" => "High"],
                    "Extra virgin olive oil" => ["value" => "1", "option" => "High"],
                    "Whole milk"             => ["value" => "1", "option" => "High"],
                    "Potato chips"           => ["value" => "1", "option" => "High"],
                    "Salmon"                 => ["value" => "1", "option" => "High"],
                    "Dark chocolate"         => ["value" => "1", "option" => "High"],
                    "Macadamia nuts"         => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 5,
                'question_text'  => 'Which one of these foods has the most iron?',
                'options'        => [
                    "Spinach, cooked, 1/2 cup"  => "0",
                    "Brown rice, cooked, 1 cup" => "0",
                    "Grilled steak, 130g"       => "0",
                    "Tuna, small tin, 125g"     => "0",
                    "Almonds/cashews, 30 nuts"  => "0",
                ],
                'correct_answer' => [
                    "Spinach, cooked, 1/2 cup" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 6,
                'question_text'  => 'Approximately how many decisions do we make every day about what we eat?',
                'options'        => [
                    "50-100"  => "0",
                    "100-200" => "0",
                    "200-300" => "0",
                    "300-400" => "0",
                ],
                'correct_answer' => [
                    "50-100" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 7,
                'question_text'  => 'What percentage of people struggle with making healthy food choices?',
                'options'        => [
                    "60%" => "0",
                    "70%" => "0",
                    "80%" => "0",
                    "90%" => "0",
                ],
                'correct_answer' => [
                    "60%" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 8,
                'question_text'  => 'How many calories does the average person consume in a day?',
                'options'        => [
                    "1500-2000" => "0",
                    "2000-2500" => "0",
                    "2500-3000" => "0",
                    "3000-3500" => "0",
                ],
                'correct_answer' => [
                    "1500-2000" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 9,
                'question_text'  => 'What is the most common reason people give up on their nutrition goals?',
                'options'        => [
                    "Lack of time"       => "0",
                    "Lack of knowledge"  => "0",
                    "Lack of motivation" => "0",
                    "Lack of support"    => "0",
                ],
                'correct_answer' => [
                    "Lack of time" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 10,
                'question_text'  => 'How many different nutrients does the human body need to function properly?',
                'options'        => [
                    "20-30" => "0",
                    "30-40" => "0",
                    "40-50" => "0",
                    "50-60" => "0",
                ],
                'correct_answer' => [
                    "20-30" => "1",
                ],
            ],
        ];

        // Insert nutrition questions
        foreach ($nutritionQuestions as $question) {
            QuizQuestion::create($question);
        }

        $this->command->info('Quiz questions seeded successfully!');
    }
}
