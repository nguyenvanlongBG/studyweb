<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCompetition extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_id',
        'test_id',
        'user_id',
        'role'
    ];
    public function questionTests(){
        $this->belongsTo(Question::class);
    }
    public function tests(){
        $this->belongsTo(Test::class);
    }
     public function users(){
        $this->belongsTo(User::class);
    }
}
