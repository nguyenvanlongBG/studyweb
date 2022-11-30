<?php

namespace Database\Seeders;

use App\Models\ItemSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $itemSubjects = [
        	 ['Tính', '1'],
            ['Giải phương trình', '1'],
        ];
 
        foreach ($itemSubjects as $itemSubject) {
            ItemSubject::create([
                'name' => $itemSubject[0],
                'subject_id' => $itemSubject[1],
                
            ]);
        }
    }
}
