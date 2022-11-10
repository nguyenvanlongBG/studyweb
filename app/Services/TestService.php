<?php
namespace App\Services;

use App\Models\AnswerTest;
                                                     use App\Repositories\ChooseQuestion\ChooseQuestionRepository;
use App\Repositories\Question\QuestionTestRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionTestRepository $questionTestRepository;

private AnswerTest $answerTestRepository;
public function __construct(TestRepository $testRepository, QuestionTestRepository $questionTestRepository,  ChooseQuestionRepository $choiceQuestionRepository  )
{
    $this->testRepository=$testRepository;
    $this->questionTestRepository=$questionTestRepository;
    $this->choiceQuestionRepository=$choiceQuestionRepository;
}
public function list(){
return $this->testRepository->all();
}
public function create(Request $request){
$data=[
    'name'=>$request['name'],
    'access'=>$request['access'],
    'status'=>$request['status'],
    'time_start'=>$request['time_start'],
    'time_end'=>$request['time_end'],
];
if($this->testRepository->create($data)){
    return true;
}
return false;
}
public function show($id){
   
    $test=$this->testRepository->find($id);

    $data=[
        'test'=>$test,
    ];
    
    return $this->sendResponse($data, "OK");
}
}
?>