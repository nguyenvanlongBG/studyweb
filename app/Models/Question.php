<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'content',
        'user_id',
        'latex',
        'subject_id',
        'type',
        // 0: Bình thường, 1: Điền đáp án, 2: Trắc nghiệm, 3 Tự luận
        'note',
        'scope'
        // 0 Puclic Forum, 1: Private
    ];
         public function users(){
        $this->belongsTo(User::class);
    }
      public function answers(){
        $this->hasMany(Answer::class);
    }
     
}
