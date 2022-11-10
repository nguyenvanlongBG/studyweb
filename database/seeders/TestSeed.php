<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $tests = [
        	 ['Cuối kì', '0', '1','16:03:10', '18:03:10','1000'],
        ];
 
        foreach ($tests as $test) {
            User::create([
                'name' => $test[0],
                'access' => $test[1],
                'status' => $test[2],
                'time_start'=>$test[3],
                'time_end'=>$test[4]
            ]);
        }
    }
}
