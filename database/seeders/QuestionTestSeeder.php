<?php

namespace Database\Seeders;

use App\Models\QuestionTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $questionsTest = [
        	 ['1+1=', '1','1', '1'],
             ['1+2=', '1','1', '1'],
        	 ['2+2=', '1','1', '1'],
        	 ['2*2=', '1','1', '1'],


        ];
 
        foreach ($questionsTest as $question) {
            QuestionTest::create([
                'content' => $question[0],
                'point' => $question[1],
                'test_id' => $question[2],
                'item_subject_id'=>$question[3]
            
            ]);
        }

    }
}
