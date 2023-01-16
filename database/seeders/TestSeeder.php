<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $tests = [
        	 ['Cuối kì 1','0',null, '0',1,0, '4', '0','2','200','Bài thi nâng cao','11:02:11', '12:02:11'],
              ['Cuối kì 2','0',null, '0',1,0, '4', '0','2','200','Bài thi Olympic','11:02:11', '12:02:11'],
        ];
 
        foreach ($tests as $test) {
            Test::create([
                'name' => $test[0],
                'type' => $test[1],
                'belong_id'=>$test[2],
                'scope' => $test[3],
                'allowRework'=>$test[4],
                'markOption'=>$test[5],
                'fee' => $test[6],
                'candidates'=>$test[7],
                'total_page'=>$test[8],
                'reward_init'=>$test[9],
                'note'=>$test[10],
                'time_start'=>$test[11],
                'time_finish'=>$test[12]
            ]);
        }
    }
}
