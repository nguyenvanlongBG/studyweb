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
        // 0: Bình thường, 1: Điền đáp án, 2: Trắc nghiệm, 3 Tự luận, 4 Câu hỏi nhỏ, 5 Câu hỏi lớn Ảnh, 7 Câu hỏi lớn ô chũ
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
    public function getQuestionNoResult(){
        $sendQuestion = $this->leftJoin('question_dos', 'question_dos.question_id', '=', 'questions.id')->first();
        $sendQuestion['results']=[];
        return $sendQuestion;
    }
    public function getQuestionHasResult(){
        $sendQuestion = $this->leftJoin('question_dos', 'question_dos.question_id', '=', 'questions.id')->select('questions.*','question_dos.type As type_belong', 'question_dos.question_id as question_id','question_dos.belong_id as belong_id', 'question_dos.point as point', 'question_dos.index as index')->first();
        return $sendQuestion;
    }
}
