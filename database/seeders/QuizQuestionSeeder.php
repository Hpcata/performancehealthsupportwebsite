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
                'question_text'  => 'Do you think these foods are high or low in carbohydrate? (Select one answer per food)',
                'options'        => [
                    "Chicken"      => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Baked beans"  => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Grain bread"  => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Avocado"      => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Weet-bix"     => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Fruit yogurt" => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Crumpets"     => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Cream"        => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Chicken"      => ["value" => "1", "option" => "Low"],
                    "Baked beans"  => ["value" => "1", "option" => "High"],
                    "Grain bread"  => ["value" => "1", "option" => "High"],
                    "Avocado"      => ["value" => "1", "option" => "Low"],
                    "Weet-bix"     => ["value" => "1", "option" => "High"],
                    "Fruit yogurt" => ["value" => "1", "option" => "High"],
                    "Crumpets"     => ["value" => "1", "option" => "High"],
                    "Cream"        => ["value" => "1", "option" => "Low"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 2,
                'question_text'  => 'Do you think these foods are high or low in protein? (Select one answer per food)',
                'options'        => [
                    "Salmon"            => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Baked beans"       => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Grapes"            => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Hummus"            => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Cornflakes cereal" => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Almonds"           => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Flavoured milk"    => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Ice cream"         => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Oat milk"          => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Salmon"            => ["value" => "1", "option" => "High"],
                    "Baked beans"       => ["value" => "1", "option" => "High"],
                    "Grapes"            => ["value" => "1", "option" => "Low"],
                    "Hummus"            => ["value" => "1", "option" => "Low"],
                    "Cornflakes cereal" => ["value" => "1", "option" => "Low"],
                    "Almonds"           => ["value" => "1", "option" => "High"],
                    "Flavoured milk"    => ["value" => "1", "option" => "High"],
                    "Ice cream"         => ["value" => "1", "option" => "Low"],
                    "Oat milk"          => ["value" => "1", "option" => "Low"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 3,
                'question_text'  => 'Do you think these foods are high or low in fat? (Select one answer per food)',
                'options'        => [
                    "Avocado"              => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Baked beans"          => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Cottage cheese"       => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Peanut butter"        => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Crumpets"             => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Cheddar/Tasty cheese" => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Avocado"              => ["value" => "1", "option" => "High"],
                    "Baked beans"          => ["value" => "1", "option" => "Low"],
                    "Cottage cheese"       => ["value" => "1", "option" => "Low"],
                    "Peanut butter"        => ["value" => "1", "option" => "High"],
                    "Crumpets"             => ["value" => "1", "option" => "Low"],
                    "Cheddar/Tasty cheese" => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 4,
                'question_text'  => 'Do you think these foods are high or low in healthy fats? (Select one answer per food)',
                'options'        => [
                    "Butter"                 => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Extra Virgin Olive Oil" => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Full cream milk"        => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Potato chips"           => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Salmon"                 => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Dark chocolate"         => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                    "Macadamia Nuts"         => ["High" => "0", "Low" => "0", "Unsure" => "0"],
                ],
                'correct_answer' => [
                    "Butter"                 => ["value" => "1", "option" => "Low"],
                    "Extra Virgin Olive Oil" => ["value" => "1", "option" => "High"],
                    "Full cream milk"        => ["value" => "1", "option" => "Low"],
                    "Potato chips"           => ["value" => "1", "option" => "Low"],
                    "Salmon"                 => ["value" => "1", "option" => "High"],
                    "Dark chocolate"         => ["value" => "1", "option" => "Low"],
                    "Macadamia Nuts"         => ["value" => "1", "option" => "High"],
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 5,
                'question_text'  => 'Which of these foods has the most iron? (Select one answer)',
                'options'        => [
                    "Spinach, cooked, 1/2 cup"  => "0",
                    "Brown rice, cooked, 1 cup" => "0",
                    "Grilled steak, 130g"       => "0",
                    "Tuna, small tin, 90g"      => "0",
                    "Almonds/cashews, ~30 nuts" => "0",
                ],
                'correct_answer' => [
                    "Grilled steak, 130g" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 6,
                'question_text'  => 'Which of these foods has the most calcium? (Select one answer)',
                'options'        => [
                    "Baby spinach, 1 cup" => "0",
                    "Firm tofu, 100g"     => "0",
                    "Tuna, small tin, 90 g" => "0",
                    "Almonds, 1/2 cup"   => "0",
                ],
                'correct_answer' => [
                    "Firm tofu, 100g" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 7,
                'question_text'  => 'Which of these foods has the most fibre? (Select one answer)',
                'options'        => [
                    "Banana, 1 large"    => "0",
                    "Raw oats, 1/2 cup"  => "0",
                    "Cashews, 1 handful" => "0",
                    "Broccoli, 1/2 cup"  => "0",
                ],
                'correct_answer' => [
                    "Raw oats, 1/2 cup" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 8,
                'question_text'  => 'Approximately how many decisions do we make every day about what we eat?',
                'options'        => [
                    "50-100"  => "0",
                    "100-150" => "0",
                    "150-200" => "0",
                    "Over 200" => "0",
                ],
                'correct_answer' => [
                    "Over 200" => "1",
                ],
            ],
            [
                'form_slug'      => 'nutrition',
                'question_index' => 9,
                'question_text'  => 'Which of the following is NOT a \'Macronutrient\'?',
                'options'        => [
                    "Iron"         => "0",
                    "Carbohydrate" => "0",
                    "Protein"      => "0",
                    "Alcohol"      => "0",
                    "Fat"          => "0",
                ],
                'correct_answer' => [
                    "Iron" => "1",
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
