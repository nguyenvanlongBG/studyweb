<?php
namespace App\Services;

use App\Models\AnswerTest;
use App\Models\CorrectAnswer;
use App\Models\Exam;
use App\Models\QuestionTest;
use App\Models\Test;
use App\Models\UserTest;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Choosed\ChoosedRepository;
use App\Repositories\CorrectAnswer\CorrectAnswerRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamService extends BaseService{
private ExamRepository $examRepository;
private QuestionRepository $questionRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;
public function __construct(ExamRepository $examRepository, QuestionRepository $questionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository )
{
    $this->examRepository=$examRepository;
    $this->questionRepository = $questionRepository;
    $this->answerQuestionTestRepository = $answerQuestionTestRepository;
}
public function list($request){
        $condition = [];
         $user = Auth::user();
        if($request->idTest){
            array_push($condition, ['test_id','=',$request->idTest]);
             if($user->role==6){
                array_push($condition, ['user_id','=',$user->id]);
                return $this->examRepository->findWhere($condition);
            }else{
                if($user->role==1||$user->role==4){
                    $userTest = UserTest::where(['user_id', '=', $user->id])->where(['test_id', '=', $request['idTest']]);
                    if($request['userId']){
                       array_push($condition, ['user_id','=',$request['userId']]);
                    }
                    return $this->examRepository->findWhere($condition);
                }
            
            }
        }
        return $this->sendError("Authorized");
}

public function createNew($data){
   
        $user = Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $data['test_id']])->first();
        $test = Test::find($data['test_id']);
        if($test){
            if($userTest==null){
                UserTest::create(['user_id' => $user->id, 'test_id' => $data['test_id'], 'role'=>0]);
                if($userTest->role==0){
                        $exam=$this->examRepository->create(['user_id'=>$user->id, 'test_id'=>$data['test_id'], 'point'=>0,'type'=>true, 'is_complete'=>false, 'is_marked'=>false]);
                        return $this->sendResponse($exam->id, "Sucessful");
                    }
                }else{
                    if($userTest->role==1){
                    
                        $exam=$this->examRepository->create(['user_id'=>$user->id, 'test_id'=>$data['test_id'], 'point'=>0,'type'=>false, 'is_complete'=>false, 'is_marked'=>false]);
                        return $this->sendResponse($exam->id, "Sucessful");
                    }
                }
            }
        return $this->sendError("Error", "Không tạo được bài thi");
    }
    public function rework($data){
        $user = Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $data['test_id']])->first();
        $test=Test::find($data['test_id']);
        if($test){
            if($userTest!=null){
                    if($userTest->role==0){
                      if($test->allow_rework){
                       $exam=$this->examRepository->create(['user_id'=>$user->id, 'test_id'=>$data['test_id'], 'point'=>0,'type'=>true, 'is_complete'=>false, 'is_marked'=>false]);
                        return $this->sendResponse($exam->id, "Sucessful");
                      }   
                    }
                }else{
                    if($userTest->role==1){
                        $exam = $this->examRepository->findWhere(['user_id'=>$user->id, 'test_id'=>$data['test_id'],'is_complete'=> false])->first();
                        if($exam==null){
                              $exam=$this->examRepository->create(['user_id'=>$user->id, 'test_id'=>$data['test_id'], 'point'=>0,'type'=>false, 'is_complete'=>false, 'is_marked'=>false]);
                              return $this->sendResponse($exam->id, "Sucessful");
                        }
                        return $this->sendError("Error", "Không làm lại được");
                    }
            }
        }
        return $this->sendError("Error", "Không tạo được bài thi");
    }
    public function update($request){
        // dd($request->all());
        $answers=AnswerTest::upsert($request->all(), ['question_id', 'exam_id'], ['answer']);
        // $answers=AnswerTest::upsert($request->all(), ['question_id', 'exam_id'], ['answer']);
        return $this->sendResponse($answers, "Successful");
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
            'questions' => [],
            'pages' => [],
        ];
        
        if($userTest!=null){
            $answers = AnswerTest::where('exam_id','=', $exam->id)->get();
            if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,1);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null;
                foreach($answers as $answer){
                    if($answer->question_id==$question['question_id']){
                            $question['answer'] = $answer->answer;
                    }
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
                $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, 1);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null; 
                foreach($answers as $answer){
                    if($answer->question_id==$question['question_id']){
                            $question['answer'] = $answer->answer;
                    }
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
}
?>