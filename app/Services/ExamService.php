<?php
namespace App\Services;

use App\Models\CorrectAnswer;
use App\Models\QuestionTest;
use App\Repositories\Choosed\ChoosedRepository;
use App\Repositories\Exam\ExamRepository;
use Illuminate\Http\Request;

class ExamService extends BaseService{
private ExamRepository $testRepository;
private ChoosedRepository $choosedRepository;
public function __construct(ExamRepository $examRepository )
{
    $this->examRepository=$examRepository;
}
public function list(){

}
public function doTest($request){
    $sumPoint=0;
    
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
     $this->choosedRepository->create();
    $this->create($dataExam);
}
public function create($dataExam){
    if($this->examRepository->create($dataExam)){
        return true;
    }
    return false;
    }
}
?>