<?php
namespace App\Services;

use App\Models\AnswerQuestionTest;
use App\Models\ItemSubject;
use App\Models\ItemSubjectQuestion;
use App\Models\Question;
use App\Models\PropertyQuestion;
use App\Repositories\Answer\AnswerNormalRepository;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Question\PropertyQuestionRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

class QuestionService extends BaseService{

private QuestionRepository $questionRepository;
private AnswerNormalRepository $answerNormalRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;

private PropertyQuestionRepository $propertyQuestionRepository;
public function __construct(QuestionRepository $questionRepository, AnswerNormalRepository $answerNormalRepository, PropertyQuestionRepository $propertyQuestionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository)
{
    $this->questionRepository=$questionRepository;
    $this->answerNormalRepository = $answerNormalRepository;
    $this->propertyQuestionRepository= $propertyQuestionRepository;
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
                $newQuestion = $this->questionRepository->create(['content' => $request->question['content'], 'type' => $request->question['type'], 'user_id' => 1, 'latex' => $request->question['content'], 'scope' => $request->question['scope']]);
                $itemsSubject = ItemSubject::where('subject_id', '=', $newQuestion->subject_id)->get();
                $listItems=[];
                foreach($request->items['create'] as $item){
                      foreach($itemsSubject as $itemSubject){
                            if($item['id']==$itemSubject['id']){
                              $listItems[] = ['question_id' => $newQuestion->id, 'item_subject_id' => $item['id']];
                            }
                      }
                }
                ItemSubjectQuestion::insert($listItems);
               
                ;
                $result_id = null;
                if ($newQuestion->type == 2) {
                    if ($request->answer['create'] != []) {
                        $listChoose = [];
                        $result = [];
                        foreach ($request->answer['create'] as $choose) {
                            if ($choose['id'] != $request->question['result_id']) {
                                $listChoose[] = ['content' => $choose['content'], 'question_id' => $newQuestion->id];
                            } else {
                                $result = ['content' => $choose['content'], 'question_id' => $newQuestion->id];
                            }
                        }
                        $this->answerQuestionTestRepository->createMany($listChoose);
                        $chooseCorrect = $this->answerQuestionTestRepository->create($result);
                        $result_id = $chooseCorrect->id;
                    }
                } else {
                    $answerResult = $this->answerQuestionTestRepository->create(['content' => $request->answer['create'][0]['content'], 'question_id' => $newQuestion->id]);
                    $result_id = $answerResult->id;
                }
                $this->propertyQuestionRepository->create(['index' => $request->question['index'], 'point' => 1, 'question_id' => $newQuestion->id, 'page' => $request->question['page'], 'dependence_id' => $request->question['dependence_id'], 'result_id' => $result_id]);
                $sendQuestion = Question::leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id', '=', $request->question['dependence_id'])->leftJoin('answer_question_tests', 'property_questions.result_id', '=', 'answer_question_tests.id')->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id', 'answer_question_tests.content As contentResult')->where('property_questions.question_id', '=', $newQuestion->id)->first();
                $sendChoices = $this->answerQuestionTestRepository->findWhere(['question_id' => $newQuestion->id], ['*'], "");
                $data = null;
                $data['question'] = $sendQuestion;
                $data['choices'] = $sendChoices;
                DB::commit();
                return $this->sendResponse($data, "Successful");
            } else {
                $result_id = $request->question['result_id'];
                $itemsSubject = ItemSubject::where('subject_id', '=', $request->question['subject_id'])->get();
                $listItems=[];
                foreach($request->items['create'] as $item){
                      foreach($itemsSubject as $itemSubject){
                            if($item['id']==$itemSubject['id']){
                              $listItems[] = ['question_id' => $request->question['question_id'], 'item_subject_id' => $item['id']];
                            }
                      }
                }
                $listRemove = [];
                foreach($request->items['remove'] as $item){
                     $listRemove[]=$item['id'];
                }
                ItemSubjectQuestion::insert($listItems);
                ItemSubjectQuestion::where('question_id', '=', $request->question['question_id'])->whereIn('item_subject_id',$listRemove)->delete();
                if ($request->question['type'] == 2) {
                    if ($request->answer['delete'] != []) {
                        $this->answerQuestionTestRepository->deleteMore($request->answer['delete']);
                    }
                    ;
                    if ($request->answer['update'] != []) {

                        AnswerQuestionTest::upsert($request->answer['update'], ['id', 'quesiton_id'], ['content']);
                    }
                    ;

                    if ($request->answer['create'] != []) {

                        $listChoose = [];
                        $result = [];
                        foreach ($request->answer['create'] as $choose) {
                            if ($choose['id'] != $request->question['result_id']) {
                                $listChoose[] = ['content' => $choose['content'], 'question_id' => $request->question['question_id']];
                            } else {
                                $result = ['content' => $choose['content'], 'question_id' => $request->question['question_id']];
                            }
                        }
                        $this->answerQuestionTestRepository->createMany($listChoose);
                        if ($result != []) {
                            $chooseCorrect = $this->answerQuestionTestRepository->create($result);
                            $result_id = $chooseCorrect->id;
                        }

                    }
                }
                if (($result_id == null || is_int($result_id))) {
                    $property = PropertyQuestion::where('question_id', $request->question['question_id'])->first();
                    if ($property != null) {
                        $property->update(['index' => $request->question['index'], 'point' => 1, 'result_id' => $result_id]);
                    }

                }
                $sendQuestion = Question::leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id', '=', $request->question['dependence_id'])->leftJoin('answer_question_tests', 'property_questions.result_id', '=', 'answer_question_tests.id')->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id', 'answer_question_tests.content As contentResult')->where('property_questions.question_id', '=', $request->question['question_id'])->first();
                $data = null;
                $data['question'] = $sendQuestion;
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