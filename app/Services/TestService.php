<?php
namespace App\Services;

use App\Models\AnswerQuestionTest;
use App\Repositories\Answer\AnswerQuesionTestRepository;


use App\Repositories\Question\QuestionRepository;

use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionRepository $questionRepository;

private AnswerQuesionTestRepository $answerQuestionTestRepository;
public function __construct(TestRepository $testRepository, QuestionRepository $questionRepository, AnswerQuesionTestRepository $answerQuestionTestRepository )
{
    $this->testRepository=$testRepository;
    $this->questionRepository=$questionRepository;
    $this->answerQuestionTestRepository=$answerQuestionTestRepository;
}
public function list($request){
        $filters=[];
        
        if(  $request->role!=null){
              array_push($filters,['user_tests.role','=',$request->role]);
        };
        if(  $request->user_id!=null){
            array_push($filters,['user_tests.user_id','=',$request->user_id]);
        }
         if(  $request->type!=null){
             array_push($filters,['tests.type','=',$request->type]);
        }
         if(  $request->name!=null){
             array_push($filters,['tests.name','like','%'.$request->name.'%']);
        }
        if(  $request->status!=null){
              array_push($filters,['user_tests.status','=',$request->status]);
        }
        
        if(  $request->candidates!=null){
             array_push($filters,['tests.candidates','<=',$request->candidates]);
        }
        // dd($filter);
        
        return $this->testRepository->listByFilter($filters);
}
public function listQuestion($idTest, $status){
        // dd($status);
        if ($status == 0) {
            $listquestions = $this->questionRepository->findWhere(['dependence_id' => $idTest], ['*'], "");
            $dataQuestions = [];
            foreach ($listquestions as $question) {
                $choices = $this->chooseQuestionTestRepository->findWhere(['question_id' => $question['id']], ['*'], "");
                $dataQuestion = [
                    'question' => $question,
                    'choices' => $choices
                ];
                $dataQuestions[] = $dataQuestion;
            }
            ;
            return $dataQuestions;
        }else{
 $listquestions = $this->questionRepository->listDetailQuestion($idTest);
            // dd($listquestions);
            $dataQuestions = [];
            foreach ($listquestions as $question) {
                $choices = $this->chooseQuestionTestRepository->findWhere(['question_id' => $question['id']], ['*'], "");
                $dataQuestion = [
                    'question' => $question,
                    'choices' => $choices
                ];
                $dataQuestions[] = $dataQuestion;
            }
            ;
            return $dataQuestions;
        }
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