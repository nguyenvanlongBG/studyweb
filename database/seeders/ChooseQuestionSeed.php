<?php

namespace Database\Seeders;

use App\Models\ChooseQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChooseQuestionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $choosesQuestion = [
        	 ['1', '2'],
            ['1','3'],
             ['1','4'],
              ['1','5'],
               ['2','2'],
                ['2','3'],
                 ['2','4'],
                  ['2','5'],
                   ['3','3'],
                   ['3','4'],
                   ['3','6'],
                   ['3','7'],
                   ['4','4'],
                   ['4','5'],
                   ['4','6'],
                   ['4','8'],



        ];
 
        foreach ($choosesQuestion as $choose) {
            ChooseQuestion::create([
                'question_test_id' => $choose[0],
                'content' => $choose[1],
               
            ]);
        }

     
    }
}
