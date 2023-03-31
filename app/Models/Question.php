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
        $sendQuestion = $this->leftJoin('property_question_tests', 'property_question_tests.question_id', '=', 'questions.id')->first();
        $sendQuestion['results']=[];
        return $sendQuestion;
    }
    public function getQuestionHasResult(){
        $sendQuestion = $this->leftJoin('property_question_tests', 'property_question_tests.question_id', '=', 'questions.id')->first();
        $results=ResultQuestion::leftJoin('answer_question_tests','answer_question_tests.id','=','result_questions.answer_question_test_id')->where('result_questions.question_id','=',$this->id)->first();
        $sendQuestion['results']=$results;
        return $sendQuestion;
    }
}
