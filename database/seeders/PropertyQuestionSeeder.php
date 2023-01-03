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
            ['3','1','1','1','1','1'],
            ['4','1','1','2','1','2'],
            ['5','1','1','3','1','6'],
            ['6','1','1','4','1','10'],
            ['7','1','1','4','1','2'],
            ['8','1','1','5','1','2'],
            ['9','1','1','6','1','2'],
            ['10','1','2','1','1','2'],
            ['11','1','2','2','1','2'],
            ['12','1','2','3','1','2'],
            ['13','1','2','4','1','2'],
            ['14','1','2','5','1','2'],
            ['15','1','2','5','1','2'],
            ['16','1','2','5','1','2'],
            ['17','1','2','5','1','2'],
            ['18','1','2','5','1','2'],
            //  1 is ID Test
        ];
        foreach($propertyQuestions as $propertyQuestion){
             PropertyQuestion::create([
                'question_id'=>$propertyQuestion[0],
                 'point'=>$propertyQuestion[1],
                 'page'=>$propertyQuestion[2],
                 'index'=>$propertyQuestion[3],
                'dependence_id'=>$propertyQuestion[4],
                'result_id'=>$propertyQuestion[5]
        ]);
        }
       
    }
}
