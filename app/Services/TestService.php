<?php
namespace App\Services;

use App\Models\AnswerQuestionTest;
use App\Models\ChooseQuestionTest;
use App\Repositories\ChooseQuestionTest\ChooseQuestionTestRepository;
use App\Repositories\Question\QuestionTestRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionTestRepository $questionTestRepository;

private AnswerQuestionTest $answerQuestionTestRepository;
private ChooseQuestionTestRepository  $chooseQuestionTestRepository;
public function __construct(TestRepository $testRepository, QuestionTestRepository $questionTestRepository,  ChooseQuestionTestRepository $chooseQuestionTestRepository  )
{
    $this->testRepository=$testRepository;
    $this->questionTestRepository=$questionTestRepository;
    $this->chooseQuestionTestRepository=$chooseQuestionTestRepository;
}
public function list($request){
    
        $filter=array();
        if(  $request->userId!=null){
            $filter['userId']=$request->userId;
           
        }
        // dd($filter);
        return $this->testRepository->findWhere($filter, ['*'],"");
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
        'test'=>$test
    ];
    
    return $this->sendResponse($data, "OK");
}
}
?>