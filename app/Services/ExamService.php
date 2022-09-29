<?php
namespace App\Services;

use App\Models\CorrectAnswer;
use App\Models\Exam;
use App\Models\QuestionTest;
use App\Repositories\Choosed\ChoosedRepository;
use App\Repositories\Exam\ExamRepository;
use Illuminate\Http\Request;

class ExamService extends BaseService{
private ExamRepository $examRepository;
private ChoosedRepository $choosedRepository;
public function __construct(ExamRepository $examRepository, ChoosedRepository $choosedRepository )
{
    $this->examRepository=$examRepository;
    $this->choosedRepository=$choosedRepository;
}
public function list(){

}
public function doTest($request){
    $sumPoint=0;
    $dataChoosed=[];
    foreach($request['answer'] as $answer){
       $correct=CorrectAnswer::where('question_test_id','=',$answer['question_test_id'])->first();
       if($correct->correct_answer==$answer['choosed']){
        $question=QuestionTest::find($answer['question_test_id'])->first();
        $sumPoint+=$question->point;
       }
    }
    $dataExam=[
        'point'=>$sumPoint,
        'user_id'=>$request['user_id'],
        'test_id'=>$request['test_id'],
    ];
    $this->create($dataExam);
    $exam=$this->examRepository->findWhere([['user_id','=',$request['user_id']],['test_id','=',$request['test_id']]], ['*'], 'first');
    dd($exam);
    // $exam=Exam::where('user_id','=',$request['user_id'])->where('test_id','=',$request['test_id'])->latest()->first();
    foreach($request['answer'] as $answer){
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