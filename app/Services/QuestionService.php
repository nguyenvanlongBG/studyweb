<?php
namespace App\Services;

use App\Models\AnswerQuestionTest;
use App\Models\ItemSubject;
use App\Models\ItemSubjectQuestion;
use App\Models\Question;
use App\Models\QuestionDo;
use App\Models\ResultQuestion;
use App\Repositories\Answer\AnswerNormalRepository;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

class QuestionService extends BaseService{

    private TestRepository $testRepository;
private QuestionRepository $questionRepository;
private AnswerNormalRepository $answerNormalRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;

public function __construct(TestRepository $testRepository,QuestionRepository $questionRepository, AnswerNormalRepository $answerNormalRepository, AnswerQuestionTestRepository $answerQuestionTestRepository)
{
    $this->testRepository=$testRepository;
    $this->questionRepository=$questionRepository;
    $this->answerNormalRepository = $answerNormalRepository;
    $this->answerQuestionTestRepository=$answerQuestionTestRepository;
}
public function storeImage(Request $request){
$path=$request->file('content')->store('public/images');
return $path;
}
// Tạo câu hỏi trong test
public function handle($request){
        DB::beginTransaction();
        try {
            if (!is_int($request->question['question_id'])) {
                $testId=null;
                if($request->question['type']>=1&&$request->question['type']<=3){
                    $testId=$request->question['dependence_id'];
                }
                $test=$this->testRepository->find($testId);
                $subject_id=null;
                if($test->type!=3){
                    // Kiểu chỉ 1 môn học không phải trong cuộc thi
                    $subject_id=$test->subject_id;
                }else{
                    $subject_id=$request->question['subject_id'];
                }
                $newQuestion = $this->questionRepository->create(['content' => $request->question['content'],'subject_id'=>$subject_id, 'type' => $request->question['type'], 'user_id' => 1, 'mathML' => $request->question['content'], 'scope' => $request->question['scope']]);
                if($subject_id){
                    $itemsSubject = ItemSubject::where('subject_id', '=', $subject_id)->get();
                    $listItems=[];
                    foreach($request->items['create'] as $item){
                        foreach($itemsSubject as $itemSubject){
                                if($item['id']==$itemSubject['id']){
                                   $listItems[] = ['question_id' => $newQuestion->id, 'item_subject_id' => $item['id']];
                                }
                        }
                    }
                    ItemSubjectQuestion::insert($listItems);
                }
                ItemSubjectQuestion::insert($listItems);
                $result_id = [];
                if ($newQuestion->type == 2) {
                    if ($request->answer['create'] != []) {
                        foreach ($request->answer['create'] as $choose) {
                            if (!in_array($choose['id'], $request->solutions['create'])) {
                                $this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' => $newQuestion->id]);
                            } else {
                                $result=$this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' => $newQuestion->id]);
                                ResultQuestion::create(['question_id'=>$newQuestion->id,'answer_question_test_id'=> $result->id]);
                            }
                        }
                    }
                } else {
                   if ($request->answer['create'] != []) {
                        foreach ($request->answer['create'] as $choose) {
                            if (!in_array($choose['id'], $request->solutions['create'])) {
                                $this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' => $newQuestion->id]);
                            } else {
                                $result=$this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' => $newQuestion->id]);
                                ResultQuestion::create(['question_id'=>$newQuestion->id,'answer_question_test_id'=> $result->id]);
                            }
                        }
                    }
                }
                QuestionDo::create(['index' => $request->question['index'], 'point' => 1, 'question_id' => $newQuestion->id, 'belong_id' => $request->question['dependence_id'], 'type' => 1]);
                $sendQuestion=$newQuestion->getQuestionHasResult();
                // $sendQuestion = Question::leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id', '=', $request->question['dependence_id'])->leftJoin('answer_question_tests', 'property_questions.result_id', '=', 'answer_question_tests.id')->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id', 'answer_question_tests.content As contentResult')->where('property_questions.question_id', '=', $newQuestion->id)->first();
                $sendChoices = $this->answerQuestionTestRepository->findWhere(['question_id' => $newQuestion->id], ['*'], "");
                $data = null;
                $data['question'] = $sendQuestion;
                $data['choices'] = $sendChoices;
                DB::commit();
                return $this->sendResponse($data, "Successful");
            } else {
                $testId=null;
                if($request->question['type']>=1&&$request->question['type']<=3){
                    $testId=$request->question['belong_id'];
                }
                $test=$this->testRepository->find($testId);
                $subject_id=null;
                if($test->type!=3){
                    // Kiểu chỉ 1 môn học không phải trong cuộc thi
                    $subject_id=$test->subject_id;
                }else{
                    $subject_id=$request->question['subject_id'];
                }
                if($subject_id){
                    $listItems=[];
                    $itemsSubject = ItemSubject::where('subject_id', '=', $request->question['subject_id'])->get();
                    foreach($request->items['create'] as $item){
                        foreach($itemsSubject as $itemSubject){
                                if($item['id']==$itemSubject['id']){
                                $listItems[] = ['question_id' => $request->question['question_id'], 'item_subject_id' => $item['id']];
                                }
                        }
                    }
                    ItemSubjectQuestion::insert($listItems);
                }
                $listRemove = [];
                foreach($request->items['remove'] as $item){
                     $listRemove[]=$item['id'];
                }
                ItemSubjectQuestion::where('question_id', '=', $request->question['question_id'])->whereIn('item_subject_id',$listRemove)->delete();
                 if ($request->answer['delete'] != []) {
                        $this->answerQuestionTestRepository->deleteMore($request->answer['delete']);
                    }
                    ;
                    if ($request->answer['update'] != []) {

                        AnswerQuestionTest::upsert($request->answer['update'], ['id', 'quesiton_id'], ['content']);
                    }
                    ;
                    if ($request->answer['create'] != []) {
                        foreach ($request->answer['create'] as $choose) {
                           if (!in_array($choose['id'], $request->solutions['create'])) {
                                $this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' =>$request->question['question_id']]);
                            } else {
                                $result=$this->answerQuestionTestRepository->create(['content' => $choose['content'], 'question_id' => $request->question['question_id']]);
                                ResultQuestion::create(['question_id'=>$request->question['question_id'],'answer_question_test_id'=> $result->id]);
                            }
                        }
                    }
                    foreach($request->solutions['create'] as $solution){
                         if(is_int($solution)){
                                ResultQuestion::create(['question_id'=>$request->question['question_id'],'answer_question_test_id'=> $solution]);
                         }
                    }
                    foreach($request->solutions['delete'] as $solution){
                         if(is_int($solution)){
                            $result=ResultQuestion::where('answer_question_test_id', $solution)->first();
                            $result->delete();
                         }
                    }
                // if (($result_id == null || is_int($result_id))) {
                //     $property = PropertyQuestion::where('question_id', $request->question['question_id'])->first();
                //     if ($property != null) {
                //         $property->update(['index' => $request->question['index'], 'point' => 1, 'result_id' => $result_id]);
                //     }

                // }
                // $sendQuestion = Question::leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id', '=', $request->question['dependence_id'])->leftJoin('answer_question_tests', 'property_questions.result_id', '=', 'answer_question_tests.id')->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id', 'answer_question_tests.content As contentResult')->where('property_questions.question_id', '=', $newQuestion->id)->first();
                $question=$this->questionRepository->find($request->question['question_id']);
                $sendQuestion = $question->getQuestionHasResult();
                $sendAnswers = $this->answerQuestionTestRepository->findWhere(['question_id' => $question->id], ['*'], "");
                $solutions=ResultQuestion::where('question_id', $question->id)->pluck('answer_question_test_id')->toArray();
                $data['question'] = $sendQuestion;
                $data['answers'] = $sendAnswers;
                $data['solutions']=$solutions?$solutions:[];
                 DB::commit();
                return $this->sendResponse($data, "Sucessful");
            }
        }catch(Exception $e){
            DB::rollBack();
            return $this->sendError(null, "Error");
        }
}
public function questionsNormal(){
$listQuestionNormal=$this->questionRepository->findWhere([['scope','=', '0']], ['*'], '');
// dd($questionNormals);
$questionsNormal = null;
foreach($listQuestionNormal as $questionNormal){
            $dataQuestion = null;
            $solutions=$this->answerNormalRepository->findWhere(['question_id' => $questionNormal['id']], ['*'], "");
            $dataQuestion['question'] = $questionNormal;
            $dataQuestion['solutions'] = $solutions;
            $questionsNormal['questions'][] = $dataQuestion;
        }
return  $this->sendResponse($questionsNormal, "Successful");
}

public function create($request){
    $path=$this->storeImage($request);
    $data=[
        'content'=>$path,
        'user_id'=>$request['user_id']
    ];
    
    $this->questionRepository->create($data);
}
public function update(){
    
}
public function delete(){
    
}
}
?>