<?php
namespace App\Services;

use App\Repositories\Question\QuestionTestRepository;

class QuestionTestService extends BaseService{

private QuestionTestRepository $questionTestRepository;
public function __construct(QuestionTestRepository $questionTestRepository )
{
    $this->questionTestRepository=$questionTestRepository;
}
public function list(){

}
public function create($request){
    $data=[
        'content'=>$request['content'],
        'point'=>$request['point'],
        'test_id'=>$request['test_id']
    ];
    $this->questionTestRepository->create($data);
}
public function update(){
    
}
public function delete(){
    
}
}
?>