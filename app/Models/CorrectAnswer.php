<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectAnswer extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_test_id',
        'correct_answer'
    ];
    public function questionTest(){
        return $this->belongsTo(QuestionTest::class);
    }
    public function correctAnswer(){
        return $this->belongsTo(ChooseQuestion::class);
    }
}
