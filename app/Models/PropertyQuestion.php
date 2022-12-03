<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'dependence_id',
        //  ID Test or ID Big Question
        'result_id'
        //  Id Answer Test
    ];
    public function questions(){
        $this->belongsTo(Question::class);
    }
     public function results(){
        $this->belongsTo(AnswerTest::class);
    }
}
