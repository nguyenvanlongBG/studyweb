<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable=[
        'point',
        'user_id',
        'test_id',
        'type',
        'is_complete', // 0 Đang làm, 1 đã hoàn thành
        'is_marked' // 0 Chưa chấm xong, 1 đã chấm xong
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
      public function tests(){
        return $this->belongsTo(Test::class);
    }
      public function chooseds(){
        return $this->hasMany(Choosed::class);
    }
}
