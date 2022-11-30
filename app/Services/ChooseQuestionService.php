<?php
namespace App\Services;

use App\Repositories\ChooseQuestionTest\ChooseQuestionTestRepository;

class ChooseQuestionService extends BaseService
{

private ChooseQuestionTestRepository $chooseQuestionTestRepository;
public function __construct(ChooseQuestionTestRepository $chooseQuestionTestRepository )
{
    $this->chooseQuestionTestRepository=$chooseQuestionTestRepository;
}
public function list(){

}
public function create($request){
    $data=[
        'question_test_id'=>$request['question_test_id'],
        'content'=>$request['content'],
    ];
    $this->chooseQuestionTestRepository->create($data);
}
public function update(){
    
}
public function delete(){
    
}
}
?>