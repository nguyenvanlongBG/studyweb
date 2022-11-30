<?php

namespace Database\Seeders;

use App\Models\Dependence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DependenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dependences=[
            ['1','1','1','1','1'],
            ['2','1','1','1','1']
        ];
        foreach($dependences as $dependence){
            Dependence::create([
                'content'=>$dependence[0],
                'user_id'=>$dependence[1],
                'latex'=>$dependence[2],
                'dependence_id'=>$dependence[3],
                'scope'=>$dependence[4]
            ]);
        }
    }
}
