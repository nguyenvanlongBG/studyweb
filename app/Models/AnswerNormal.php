<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerNormal extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_id',
        'user_id',
        'content',
        'evaluate',
        'fee'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
     public function questions(){
        $this->belongsTo(Question::class);
    }
      public function evaluateAnswers(){
        $this->hasMany(EvalueAnswer::class);
    }  
}
