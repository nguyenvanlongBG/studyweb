<?php
namespace App\Services;
use App\Repositories\Answer\AnswerNormalRepository;
use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Answer\AnswerRepository;
use Illuminate\Http\Request;

class AnswerService extends BaseService{
private AnswerNormalRepository $answerNormalRepository;
public function __construct(AnswerNormalRepository $answerNormalRepository)
{
    $this->answerNormalRepository=$answerNormalRepository;
}
public function listByIdQuestion($id){
    // dd("OK");
return $this->answerNormalRepository->listByIdQuestion($id);
}
public function list(){

}
public function createNormalAnswer(Request $request){
    
$data=[
    'question_normal_id'=>$request['question_normal_id'],
    'content'=>$request['content'],
    'user_id'=>$request['user_id'],
];
if($this->answerNormalRepository->create($data)){
    return true;
}
return false;
}
public function showNormalAnswer($id){
        return $this->sendResponse($this->answerNormalRepository->find($id),"Sucessfully" );
}
}
?>