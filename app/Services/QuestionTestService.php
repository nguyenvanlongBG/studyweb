<?php
namespace App\Services;

use App\Repositories\Question\QuestionTestRepository;
use App\Repositories\ChooseQuestionTest\ChooseQuestionTestRepository;
use Illuminate\Support\Facades\DB;

class QuestionTestService extends BaseService{

private QuestionTestRepository $questionTestRepository;
private ChooseQuestionTestRepository $chooseQuestionTestRepository;
public function __construct(QuestionTestRepository $questionTestRepository, ChooseQuestionTestRepository $chooseQuestionTestRepository )
{
    $this->chooseQuestionTestRepository=$chooseQuestionTestRepository;
}
public function listByIdTest($idTest){
    $listquestions=$this->questionTestRepository->findWhere(['test_id'=>$idTest], ['*'], "")->get();
   return $listquestions;
}
public function create($request){
    $data=[
        'content'=>$request['content'],
        'point'=>$request['point'],
        'test_id'=>$request['test_id']
    ];
    DB::beginTransaction();
    $question=$this->questionTestRepository->create($data);
    if($question){
          DB::commit();
    }else{
        DB::rollBack();
    }
    return $this->sendResponse(null, "Successfully");
}
public function update(){
    
}
public function delete(){
    
}
}
?>