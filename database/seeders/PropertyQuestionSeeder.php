<?php

namespace Database\Seeders;

use App\Models\PropertyQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $propertyQuestions=[
            ['3','1','1'],
            ['4','1','2'],
            //  1 is ID Test
        ];
        foreach($propertyQuestions as $propertyQuestion){
             PropertyQuestion::create([
                'question_id'=>$propertyQuestion[0],
                'dependence_id'=>$propertyQuestion[1],
                'result_id'=>$propertyQuestion[2]
        ]);
        }
       
    }
}
