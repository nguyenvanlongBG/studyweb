<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectChooseQuestionTest extends Model
{
    use HasFactory;
    protected $fillable=[
'question_id',
'result_question_test_id'
    ];
    public function questionTests(){
        return $this->belongsTo(Question::class);
    }
     public function resultQuestionTests(){
        return $this->hasOne(ResultQuestionTest::class);
    }
}
