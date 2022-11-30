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
        'test_id'
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
