<?php
namespace App\Services;

use App\Repositories\Question\QuestionTestRepository;
use App\Repositories\ChooseQuestion\ChooseQuestionRepository;

class QuestionTestService extends BaseService{

private QuestionTestRepository $questionTestRepository;
private ChooseQuestionRepository $choiceQuestionRepository;
public function __construct(QuestionTestRepository $questionTestRepository, ChooseQuestionRepository $choiceQuestionRepository )
{
    $this->questionTestRepository=$questionTestRepository;
    $this->choiceQuestionRepository=$choiceQuestionRepository;
}
public function listByIdTest($idTest){
    // dd($idTest);
    $listquestions=$this->questionTestRepository->findWhere(['test_id'=>$idTest], ['*'], "");
    $dataQuestions=[];
    foreach($listquestions as $question){
        $choices=$this->choiceQuestionRepository->findWhere(['question_test_id'=>$question['id']], ['*'],"" );
        $dataQuestion=[
            'question'=>$question,
            'choices'=>$choices
        ];
        $dataQuestions[]=$dataQuestion;
    };
    
   return $dataQuestions;
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