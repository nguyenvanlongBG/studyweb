<?php

namespace Database\Seeders;

use App\Models\AnswerTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Câu trả lời của người làm
class AnswerTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $answerTests = [
            ['x=2','3', '1'],
            ['2','4', '1']
        ];
        foreach($answerTests as $answerTest){
            AnswerTest::create([
                'answer' => $answerTest[0],
                'question_id' => $answerTest[1],
                'exam_id' => $answerTest[2]
            ]);
        }
    }
}
