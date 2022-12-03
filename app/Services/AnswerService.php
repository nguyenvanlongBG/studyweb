<?php
namespace App\Services;

use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Answer\AnswerRepository;
use Illuminate\Http\Request;

class AnswerService extends BaseService{
private AnswerRepository $answerRepository;
public function __construct(AnswerRepository $answerRepository)
{
    $this->answerRepository=$answerRepository;
}
public function listByIdQuestion($id){
    // dd("OK");
return $this->answerRepository->listByIdQuestion($id);
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