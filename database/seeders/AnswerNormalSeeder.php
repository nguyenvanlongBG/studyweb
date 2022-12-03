<?php

namespace Database\Seeders;

use App\Models\AnswerNormal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerNormalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $answerNormals = [
            ['1', '1', 'https://vcdn1-dulich.vnecdn.net/2021/07/16/8-1626444967.jpg?w=1200&h=0&q=100&dpr=1&fit=crop&s=GfgGn4dNuKZexy1BGkAUNA','4', '4'],
            ['1', '2','4', '5', '4']
        ];
        foreach($answerNormals as $answerNormal){
            AnswerNormal::create(
                [
                    'question_id'=>$answerNormal[0],
                    'user_id'=>$answerNormal[1],
                    'content'=>$answerNormal[2],
                    'evaluate'=>$answerNormal[3],
                    'fee'=>$answerNormal[4]
                ]
            );
        }
       
    }
}
