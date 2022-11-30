<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $posts=[
        ['Tài liệu giải phương trình nâng cao',
        'https://vcdn1-dulich.vnecdn.net/2021/07/16/8-1626444967.jpg?w=1200&h=0&q=100&dpr=1&fit=crop&s=GfgGn4dNuKZexy1BGkAUNA',
        'Nội dung nâng cao',
        '1',
        '1',
        '0',
        '1'
     ],
             ['Tài liệu tích phân nâng cao',
        'https://vcdn1-dulich.vnecdn.net/2021/07/16/8-1626444967.jpg?w=1200&h=0&q=100&dpr=1&fit=crop&s=GfgGn4dNuKZexy1BGkAUNA',
        'Nội dung nâng cao tích phân',
        '1',
        '1',
        '0',
        '1'
]
       
     ];
     foreach($posts as $post){
        Post::create([
            'title'=>$post[0],
            'image_preview'=>$post[1],
            'content'=>$post[2],
            'type'=>$post[3],
            'user_id'=>$post[4],
            'status'=>$post[5],
            'approve'=>$post[6]
        ]);
     }
    }
}
