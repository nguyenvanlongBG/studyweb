<?php
namespace App\Services;

use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use Illuminate\Http\Request;

class CorrectAnswerService extends BaseService{
private CorrectAnswerRepository $correctAnswerRepository;
public function __construct(CorrectAnswerRepository $correctAnswerRepository)
{
    $this->correctAnswerRepository=$correctAnswerRepository;
}
public function list(){

}
public function create(Request $request){
    
$data=[
    'question_test_id'=>$request['question_test_id'],
    'correct_answer'=>$request['correct_answer'],
];
if($this->correctAnswerRepository->create($data)){
    return true;
}
return false;
}
}
?>