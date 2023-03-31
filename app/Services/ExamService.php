<?php
namespace App\Services;

use App\Models\AnswerTest;
use App\Models\CorrectAnswer;
use App\Models\Exam;
use App\Models\ItemSubject;
use App\Models\ItemSubjectQuestion;
use App\Models\QuestionTest;
use App\Models\Test;
use App\Models\UserTest;
use App\Repositories\Answer\AnswerNormalRepository;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Choosed\ChoosedRepository;
use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Question\Question;
use TheSeer\Tokenizer\Exception;

class ExamService extends BaseService{
private ExamRepository $examRepository;
    private TestRepository $testRepository;
private QuestionRepository $questionRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;
    private AnswerNormalRepository $answerNormalRepository;
public function __construct(TestRepository $testRepository,ExamRepository $examRepository, QuestionRepository $questionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository, AnswerNormalRepository $answerNormalRepository)
{
    $this->examRepository=$examRepository;
    $this->questionRepository = $questionRepository;
    $this->answerQuestionTestRepository = $answerQuestionTestRepository;
    $this->answerNormalRepository = $answerNormalRepository;
        $this->testRepository = $testRepository;
    }
public function list($id, $request){
        $filtersExam = [];
        $filterUserName = "";
        $filtersExam[]= ['test_id', '=', $id];
         $filtersExam[]= ['is_complete', '=', 1];
         $filtersExam[]= ['type', '=', 0];
        if($request->status==1){
            $filtersExam[]= ['is_marked', '=', 1];
        }
         if($request->status==2){
            $filtersExam[]= ['is_marked', '=', 0];
        }
        $exams = $this->examRepository->list($filtersExam, $filterUserName);
        return $this->sendResponse($exams, "Sucessful");
}

public function createNew($data){
   
        $user = Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $data['test_id']])->first();
        $test = Test::find($data['test_id']);
        DB::beginTransaction();
        try {
            if ($test) {
                if ($userTest == null) {
                    UserTest::create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'role' => 0]);
                    $exam = $this->examRepository->create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'point' => 0, 'type' => true, 'is_complete' => false, 'is_marked' => false]);
                      DB::commit();
                    return $this->sendResponse($exam->id, "Sucessful");
                } else {
                    if ($userTest->role == 1) {
                        $exam = $this->examRepository->create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'point' => 0, 'type' => false, 'is_complete' => false, 'is_marked' => false]);
                          DB::commit(); 
                        return $this->sendResponse($exam->id, "Sucessful");
                    }
                }
            }
           
        }catch(Exception $e){
            DB::rollBack();
             return $this->sendError("Error", "Không tạo được bài thi");
        }
    }
    public function listQuestionDo($idTest, $current_page){
        $page = $this->testRepository->findWhere(['id' => $idTest], ['total_page'], "")->first();
        $user=Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $idTest])->first();
        $exam = $this->examRepository->findWhere(['user_id' => $user->id, 'test_id' => $idTest, 'is_complete' => false], ['*'], "first");
        $startIndex = 0;
        $dataQuestions = [];
        $dataQuestions = [
            'exam_id'=>$exam->id,
            'questions' => [],
            'answers'=>[],
            'pages' => [],
        ];
        if($exam==null){
            return $this->sendError(null, "Fail");
        }
        if($userTest!=null){
            $listAnswers = AnswerTest::where('exam_id','=', $exam->id)->get();
            $answers = [];
            foreach($listAnswers as $answer){
                $answers[$answer->question_id] = $answer->answer;
            }
            if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,0);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null;
                 $question['answer'] = null;
                if (array_key_exists($question['question_id'], $answers)) {
                         $question['answer'] = $answers[$question['question_id']];
                }
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                    // dd($dataQuestion);
                }else{
                    $dataQuestion['question']=$question;         
                }
               $dataQuestions['questions'][]=$dataQuestion;
              }
            }else{
                $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, 0);
                $startIndex = $listQuestions['startIndex'];
                
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null; 
                 $question['answer'] = null;
                 if (array_key_exists($question['question_id'], $answers)) {
                         $question['answer'] = $answers[$question['question_id']];
                }
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                }else{
                        $dataQuestion['question']=$question;
                }

                $dataQuestions['questions'][]=$dataQuestion;
                }
            }    
        }
       
        
         $pages= [
            'totalPage'=> $page->total_page,
            'currentPage'=> $current_page,
            'startIndex'=>$startIndex
        ];
         $dataQuestions['pages'] = $pages;
         if($dataQuestions){
               return $this->sendResponse($dataQuestions, "Successful");
         }else{
            return $this->sendError(null, "Fail");
         }
        
    
    
}
    public function rework($data){
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $data['test_id']])->first();
            $test = Test::find($data['test_id']);
            if ($test) {
                if ($userTest != null) {
                    if ($userTest->role == 0) {
                        if ($test->allow_rework) {
                            $exam = $this->examRepository->create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'point' => 0, 'type' => true, 'is_complete' => false, 'is_marked' => false]);
                             DB::commit();
                            return $this->sendResponse($exam->id, "Sucessful");
                        }
                    }
                } else {
                    if ($userTest->role == 1) {
                        $exam = $this->examRepository->findWhere(['user_id' => $user->id, 'test_id' => $data['test_id'], 'is_complete' => false, 'is_marked' => false])->first();
                        if ($exam == null) {
                            $exam = $this->examRepository->create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'point' => 0, 'type' => false, 'is_complete' => false]);
                             DB::commit();
                            return $this->sendResponse($exam->id, "Sucessful");
                        }
                        DB::rollBack();
                        return $this->sendError("Error", "Không làm lại được");
                    }
                }
            }
           
        }catch(Exception $e){
            DB::rollBack();
            return $this->sendError("Error", "Không tạo được bài thi");
        } 
    }
    public function doExam($request){
        $user = Auth::user();
        $exam = $this->examRepository->find($request->exam_id);
        if($user->id==$exam->user_id){
        foreach($request->answers as $answer){
            $answers[$answer['question_id']] = $answer;
        }
        $query=DB::table('questions')->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $exam->test_id)->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id' );
        $data = [];  
        $questions=$query->where('page','=', $request->current_page)->get()->unique('question_id');
        foreach($questions as $question){
              if (array_key_exists($question->question_id, $answers)) {
                    $answers[$question->question_id]['exam_id']= $exam->id;
                    $data[] = $answers[$question->question_id];      
            }
        }
        }
        // dd($request->all());
        $answers=AnswerTest::upsert($data, ['question_id', 'exam_id'], ['answer']);
        // $answers=AnswerTest::upsert($request->all(), ['question_id', 'exam_id'], ['answer']);
        return $this->sendResponse($answers, "Successful");
    }
    public function mark( $idExam, $request){
        $user=Auth::user();
        $exam = $this->examRepository->find($idExam);
        if($exam){
            $userTest = UserTest::where('user_id', '=', $user->id)->where('test_id', '=', $exam->test_id)->first();
            if($userTest->role==1){
                    $query=DB::table('questions')->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $exam->test_id)->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id' ); 
                    $listQuestion=$query->where('page','=', $request->current_page)->get()->unique('question_id');
                    $questions = null;
                    foreach( $listQuestion as $question){
                        $questions[$question->question_id] = $question;
                    }
                $data = [];
                    foreach($request->mark as $answer){
                        if(array_key_exists($answer['question_id'], $questions)){
                            if ($answer['point'] <= $question->point){
                                $answer['exam_id'] = $idExam;
                                $data[]=$answer;
                            }
                        }
                    }
                $answers=AnswerTest::upsert($data, ['question_id', 'exam_id'], ['point']);
                // $answers=AnswerTest::upsert($request->all(), ['question_id', 'exam_id'], ['answer']);
                $answerEmpty = AnswerTest::where('exam_id', '=', $exam->id)->where('point', '=', null)->first();
                if($answerEmpty==null){
                    $exam->is_marked = true;
                }else{
                    $exam->is_marked = false;
                }
                $exam->point = AnswerTest::where('exam_id','=',$exam->id)->where('point', '<>', null)->sum('point');
                $exam->save();
                return $this->sendResponse($answers, "Successful");
            }
        }
        return $this->sendError(null, "fail");
    }
    public function submit($request){
        $user = Auth::user();
        $data = $request->all();       
        if ($request->examId) {
            if (is_int($request->examId)) {
                $exam = $this->examRepository->find($data['examId']);
                if(!$exam->is_complete){
                    if($user->id==$exam->user_id){
                        $exam->is_complete = 1;
                        $this->autoMark($data['examId']);
                        $exam->save();
                       return $this->sendResponse($exam->save(), "Successfully");
                    }
                }
            }
        }
        return $this->sendError("Error", "Lỗi");
    }
    public function autoMark($examId){
       $exam = $this->examRepository->find($examId);
       $listAnswers = AnswerTest::where('exam_id', '=', $examId)->get();
        $answers = [];
        foreach($listAnswers as $answer){
            $answers[$answer->question_id] = $answer;
        }
        $query=DB::table('questions')->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $exam->test_id)->leftJoin('answer_question_tests','property_questions.result_id', '=', 'answer_question_tests.id' )->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id','answer_question_tests.content As contentResult' );
        $data = [];  
        $questions=$query->get()->unique('question_id');
        foreach($questions as $question){
              if (array_key_exists($question->question_id, $answers)) {
                         if($question->type==2){
                            if($question->result_id==$answers[$question->question_id]->answer){
                                  $answers[$question->question_id]->point = $question->point;
                            }else{
                                 $answers[$question->question_id]->point = 0;
                            }
                            $answers[$question->question_id]->save();
                         }
                         if($question->type==1){
                            if($question->contentResult==$answers[$question->question_id]->answer){
                                  $answers[$question->question_id]->point = $question->point;
                            }else{
                                $answers[$question->question_id]->point = 0;
                            }
                            $answers[$question->question_id]->save();
                         }
                }
        }
        $exam->point = AnswerTest::where('exam_id','=',$exam->id)->where('point', '<>', null)->sum('point');
        $exam->save();
    }
    public function history($idTest, $request){
        $test = Test::find($idTest);
        $total_page = $test->total_page;
        $idExam = $request->idExam;
        $current_page = $request->current_page;
        $user=Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $idTest])->first();
        if($idExam){
            $exam = $this->examRepository->findWhere(['id' => $idExam, 'is_complete' => 1], ['*'], "first");
        }else{
            $exam = $this->examRepository->findWhere(['user_id' => $user->id, 'test_id' => $idTest, 'is_complete' => 1  ], ['*'], "first");
        }
         if($exam==null){
            return $this->sendResponse(null, "");
        }
        $startIndex = 0;
        $dataQuestions = [];
        $dataQuestions = [
            'exam_id'=>$exam->id,
            'point'=>$exam->point,
            'questions' => [],
            'pages' => [],
            'isOwner'=>false
        ];
      
        if($userTest!=null){
                if($userTest->role==0){
                    $dataQuestions['isOwner'] = false;
                }
            ;
                if($userTest->role==1){
                        $dataQuestions['isOwner'] = true;
                }
            ;
            $listAnswers = AnswerTest::where('exam_id','=', $exam->id)->get();
            $answers = [];
            foreach($listAnswers as $answer){
                $answers[$answer->question_id] = $answer;
            }
            if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,1);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null;
                $question['answer'] = null;
                if (array_key_exists($question['question_id'], $answers)) {
                         $question['answer'] = $answers[$question['question_id']]->answer;
                         $question['markPoint'] = $answers[$question['question_id']]->point;
                }
                
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                    // dd($dataQuestion);
                }else{
                    $dataQuestion['question']=$question;         
                }
                    $solutions = [];
                if($question['scope']==0){
                       $solutions=$this->answerNormalRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");;
                }
                $dataQuestion['solutions']=$solutions;
                $dataQuestions['questions'][]=$dataQuestion;
              }
            }else{
                $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, 1);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null; 
                 $question['answer'] = null;
                if (array_key_exists($question['question_id'], $answers)) {
                         $question['answer'] = $answers[$question['question_id']]->answer;
                         $question['markPoint'] = $answers[$question['question_id']]->point;
                }
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                }else{
                        $dataQuestion['question']=$question;
                }
                $solutions = [];
                if($question['scope']==0){
                    $solutions=$this->answerNormalRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");;
                }
                $dataQuestion['solutions']=$solutions;
                $dataQuestions['questions'][]=$dataQuestion;
                }
            }    
        }
         $pages= [
            'totalPage'=> $total_page,
            'currentPage'=> $current_page,
            'startIndex'=>$startIndex
        ];
         $dataQuestions['pages'] = $pages;
         if($dataQuestions){
               return $this->sendResponse($dataQuestions, "Successful");
         }else{
            return $this->sendError(null, "Fail");
         }
    }
    public function listQuestionMark(){

    }
    public function nummericalQuestionHistory($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $list = [];
        if($request->exam_id==null){
             return $this->sendError(null, "Error");
        };
        $exam=Exam::find($request->exam_id);
        $user=Auth::user();
        if($user->id!=$exam->user_id){
             return $this->sendError(null, "Error");
        };
        $listAnswers = AnswerTest::where('exam_id', '=', $request->exam_id)->get();
        $answers = [];
        foreach($listAnswers as $answer){
            $answers[$answer->question_id] = $answer->answer;
        }
        for($i=1;$i<=$test->total_page;$i++){
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  1);
            foreach($listQuestionPage['questions'] as $question){
                $item = [];
                if($question->type==2){
                    if($question->result_id!=null){
                        if(array_key_exists($question->question_id, $answers)){
                            if($question->result_id==$answers[$question->question_id]){
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 2];
                            }else{
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 3];
                            }
                        }else{
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                        }
                    }else{ 
                        if(array_key_exists($question->question_id, $answers)){
                            $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                        }else{
                            $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                        } 
                      
                    };
                }else{
                    if(array_key_exists($question->question_id, $answers)){
                       if($question->type==1){
                        if($question->contentResult!=""){
                            if($answers[$question->question_id]==$question->contentResult){
                                $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 2];
                                }else{
                                $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 3];
                                }
                        }else{  
                         $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                        };
                    }else{
                         $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];

                    }
                    }else{
                        $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                    }
                    
                    
                }
                
                $list[] = $item;
            };
       }
        $data = ['data' => $list];
        return $this->sendResponse($data, "Succesfully");
    }
    public function reportExam($id,$request){
        $exam=$this->examRepository->find($id);
        $test=$this->testRepository->find($exam->test_id);
        $itemsSubject = ItemSubject::where('subject_id', '=', $test->subject_id)->get();
        $data=['correct'=>[],'fail'=>[],'dont'=>[]];
        $columnsName = [];
        foreach($itemsSubject as $item){
            $columnsName[] = $item->name;
            $data['correct'][$item->id] = 0;
            $data['fail'][$item->id] = 0;
            $data['dont'][$item->id] = 0;
        }
        $listAnswers = AnswerTest::where('exam_id', '=', $exam->id)->get();
        $answers = [];
        foreach($listAnswers as $answer){
            $answers[$answer->question_id] = $answer;
        };
        $query=DB::table('questions')->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $exam->test_id)->leftJoin('answer_question_tests','property_questions.result_id', '=', 'answer_question_tests.id' )->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id','answer_question_tests.content As contentResult' );
        $questions=$query->get()->unique('question_id');
        foreach($questions as $question){
            $items=ItemSubjectQuestion::where('question_id',$question->question_id)->get();
            if (array_key_exists($question->question_id, $answers)) {
                foreach($items as $item){
                    $data['correct'][$item->item_subject_id] += number_format(($answers[$question->question_id]->point)/count($items),2);
                    $data['fail'][$item->item_subject_id] += number_format(($question->point- $answers[$question->question_id]->point)/count($items),2);
                } 
            }else{
                foreach($items as $item){
                    number_format($data['dont'][$item->item_subject_id] += $question->point/count($items),2);
                } 
            }
        };
        $dataSend=['columns_name'=>$columnsName, 'series'=>[['name'=>"Làm đúng",'data'=>array_values($data['correct'])],['name'=>'Làm sai', 'data'=>array_values($data['fail'])], ['name'=>'Chưa làm', 'data'=>array_values($data['dont'])]]];
        return $this->sendResponse($dataSend, "Successfully");
    }
    
}
?>