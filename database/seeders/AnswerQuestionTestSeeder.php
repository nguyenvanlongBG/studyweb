<?php

namespace Database\Seeders;

use App\Models\AnswerQuestionTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Câu trả lời của bài cho
class AnswerQuestionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $answerQuestionTests = [
            ['3', 'x=2'],
            ['4', 'x=10'],
            ['4', '20'],
            ['4', '50'],
            ['4', '100']
        ];
        foreach( $answerQuestionTests as $answerQuestionTest){
            AnswerQuestionTest::create([
                'question_id' => $answerQuestionTest[0],
                'content' => $answerQuestionTest[1]
            ]);
        }
    }
}
