<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'answer',
        // Choose Question it is ID Choose, fill Question it is Value, Essay it is result
        'question_id',
        'exam_id'
    ];
    public function questions(){
        $this->belongsTo(Question::class);
    }
    public function exams(){
        $this->belongsTo(Exam::class);
    }
}
