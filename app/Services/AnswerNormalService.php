<?php
namespace App\Services;

use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Answer\AnswerNormalRepository;
use Illuminate\Http\Request;

class AnswerNormalService extends BaseService{
private AnswerNormalRepository $answerNormalRepository;
public function __construct(AnswerNormalRepository $answerNormalRepository)
{
    $this->answerNormalRepository=$answerNormalRepository;
}
public function getAnswersByIdQuestionNormal($id){
    // dd("OK");
return $this->answerNormalRepository->getAnswersByIdQuestion($id);
}
public function list(){

}
public function create(Request $request){
    
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
}
?>