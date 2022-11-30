<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choosed extends Model
{
    use HasFactory;
    protected $fillable=[
'choosed',
'question_id',
'exam_id'
    ];
    public function chooseds(){
        $this->belongsTo(ChooseQuestionTest::class);
    }
    public function exams(){
        $this->belongsTo(Exam::class);
    }
    public function questions(){
         $this->belongsTo(Question::class);
    }
}
