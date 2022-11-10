<?php
namespace App\Services;

use App\Repositories\ChooseQuestion\ChooseQuestionRepository;

class ChooseQuestionService extends BaseService
{

private ChooseQuestionRepository $chooseQuestionRepository;
public function __construct(ChooseQuestionRepository $chooseQuestionRepository )
{
    $this->chooseQuestionRepository=$chooseQuestionRepository;
}
public function list(){

}
public function create($request){
    $data=[
        'question_test_id'=>$request['question_test_id'],
        'content'=>$request['content'],
    ];
    $this->chooseQuestionRepository->create($data);
}
public function update(){
    
}
public function delete(){
    
}
}
?>