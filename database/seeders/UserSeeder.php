<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->truncate();

        $users = [
        	 ['Nguyễn Văn A', 'nguyenvana@gmail.com', '123456789','1', '1','1000', '1000','2000'],
            ['Nguyễn Thị B', 'nguyenthib@gmail.com', '123456789','1','1','100','2000'],
        ];
 
        foreach ($users as $user) {
            User::create([
                'name' => $user[0],
                'email' => $user[1],
                'password' => $user[2],
                'role_id' => $user[3],
                'level'=>$user[4],
                'point'=>$user[5],
                'asset'=>$user[6]
            ]);
        }

        // Schema::enableForeignKeyConstraints();
    }
}
