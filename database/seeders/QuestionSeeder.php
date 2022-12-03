<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $questions = [
        	 ['1+1=', '1','', '1','0', '0'],
             ['1+2=', '1','', '1','0', '0'],
        	 ['Giải phương trình x+2=4', '1','','1', '1', '0'],
             ['Giải phương trình x+2=4', '1', '','1', '2', '1']
        ];
 foreach ($questions as $question) {
            Question::create([
                'content' => $question[0],
                'user_id' => $question[1],
                'latex' => $question[2],
                'subject_id'=>$question[3],
                'type'=>$question[4],
                'scope'=>$question[5],
               
            ]);
        }

    }
}
