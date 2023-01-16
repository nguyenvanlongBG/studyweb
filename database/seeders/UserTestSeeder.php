<?php

namespace Database\Seeders;

use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $user_tests = [
        	 ['1', '1', '2'],
            ['1', '1', '2'],
        ];
 
        foreach ($user_tests as $user_test) {
            UserTest::create([
                'user_id' => $user_test[0],
                'test_id' => $user_test[1],
                'role' => $user_test[2],
                'status' => $user_test[3],
                
            ]);
        }
    }
}
