<?php
namespace App\Services;

use App\Models\CorrectAnswer;
use App\Models\Exam;
use App\Models\QuestionTest;
use App\Repositories\Choosed\ChoosedRepository;
use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Exam\ExamRepository;
use Illuminate\Http\Request;

class ExamService extends BaseService{
private ExamRepository $examRepository;
private ChoosedRepository $choosedRepository;
private CorrectAnswerRepository $correctAnswerRepository;
public function __construct(ExamRepository $examRepository, ChoosedRepository $choosedRepository, CorrectAnswerRepository $correctAnswerRepository )
{
    $this->examRepository=$examRepository;
    $this->choosedRepository=$choosedRepository;
    $this->correctAnswerRepository=$correctAnswerRepository;
}
public function list(){

}
public function doTest($request){
    $sumPoint=0;
    $dataChoosed=[];
    foreach($request['answer'] as $answer){
        $correct=$this->correctAnswerRepository->findWhere(['question_test_id'=>$answer['question_test_id']], ['*'], 'first');
    //    $correct=CorrectAnswer::where('question_test_id','=',$answer['question_test_id'])->first();
       if($correct->correct_answer==$answer['choosed']){
        $question=QuestionTest::find($answer['question_test_id'])->first();
        $sumPoint+=$question->point;
       }
    }
    // dd($sumPoint);
    $dataExam=[
        'point'=>$sumPoint,
        'user_id'=>$request['user_id'],
        'test_id'=>$request['test_id'],
    ];
    $this->create($dataExam);
    $exam=$this->examRepository->findWhere([['user_id','=',$request['user_id']],['test_id','=',$request['test_id']]], ['*'], 'latest');
    // $exam=Exam::where('user_id','=',$request['user_id'])->where('test_id','=',$request['test_id'])->latest()->get();
    // dd($exam->id);
    foreach($request['answer'] as $answer){
        // dd($exam->id);
        $choosed=[
            'choosed'=>$answer['choosed'],
            'question_test_id'=>$answer['question_test_id'],
            'exam_id'=>$exam->id, 
        ];

        $dataChoosed[]= $choosed;
    }
    if( $this->choosedRepository->createMany($dataChoosed)){
        return true;
    }else{
        return false;
    };
   
}
public function create($dataExam){
    if($this->examRepository->create($dataExam)){
        return true;
    }
    return false;
    }
}
?>