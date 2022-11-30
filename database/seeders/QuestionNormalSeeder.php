<?php

namespace Database\Seeders;

use App\Models\QuestionNormal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionNormalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionNormals=[
                            ['Giải phương trình x+2=4', '', '0', '1'],
                            ['Giải phương trình x+2=4', '', '0', '1']
        ];
        foreach($questionNormals as $questionNormal){
            QuestionNormal::create([
                'content'=>$questionNormal[0],
                'latex'=>$questionNormal[1],
                'type'=>$questionNormal[2],
                'user_id'=>$questionNormal[3]
            ]);
        }
    }
}
