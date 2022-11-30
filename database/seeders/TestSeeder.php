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
        	 ['Cuối kì 1','0', '0', '4','5', '0','200','11:02:11', '12:02:11'],
              ['Cuối kì 2','0', '0', '4','5', '0','200','11:02:11', '12:02:11'],
        ];
 
        foreach ($tests as $test) {
            Test::create([
                'name' => $test[0],
                'type' => $test[1],
                'scope' => $test[2],
                'fee' => $test[3],
                'point'=>$test[4],
                'candidates'=>$test[5],
                'reward_init'=>$test[6],
                'time_start'=>$test[7],
                'time_finish'=>$test[8]
            ]);
        }
    }
}
