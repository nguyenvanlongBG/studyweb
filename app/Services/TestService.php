<?php
namespace App\Services;
use App\Models\AnswerQuestionTest;
use App\Models\AnswerTest;
use App\Models\Exam;
use App\Models\PropertyQuestion;
use App\Models\Question;
use App\Models\Test;
use App\Models\UserTest;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Question\PropertyQuestionRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionRepository $questionRepository;
private PropertyQuestionRepository $propertyQuestionRepository;
private ExamRepository $examRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;// private AnswerQuesionTestRepository $answerQuestionTestRepository;
public function __construct(TestRepository $testRepository, QuestionRepository $questionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository, PropertyQuestionRepository $propertyQuestionRepository, ExamRepository $examRepository)
{
    $this->testRepository=$testRepository;
    $this->questionRepository=$questionRepository;
    $this->answerQuestionTestRepository=$answerQuestionTestRepository;
    $this->propertyQuestionRepository=$propertyQuestionRepository;
    $this->examRepository=$examRepository;
}
public function list($request){
        $filters=[];
       // Bộ đề của giáo viên
        if(  $request->role!=null){
              array_push($filters,['user_tests.role','=',$request->role]);
        };
        // Bộ đề mình có quyền làm, chỉnh sửa
        if(  $request->user_id!=null){
            array_push($filters,['user_tests.user_id','=',$request->user_id]);
        }
        // Test competition or standard Test 
         if(  $request->type!=null){
             array_push($filters,['tests.type','=',$request->type]);
        }
        // Find name test
         if(  $request->name!=null){
             array_push($filters,['tests.name','like','%'.$request->name.'%']);
        }
        // Kích hoạt
        if(  $request->status!=null){
              array_push($filters,['user_tests.status','=',$request->status]);
        }
        // Số người tham gia
        if(  $request->candidates!=null){
             array_push($filters,['tests.candidates','<=',$request->candidates]);
        }
        // dd($filter);
        
        return $this->testRepository->listByFilter($filters);
}
public function create($user, $request){{
        $data=[
            'type'=>$request['type'],
            'belong_id'=>$request['belong_id'],
            'scope'=>0,
            'fee'=>500,
            'name'=>$request['name'],
            'note'=>$request['note'],
            'allow_rework'=>$request['allow_rework'],
            'reward_init'=>$request['reward_init'],
            'mark_option'=>$request['mark_option'],
            'total_page'=>1,
            'candidates'=>0,
            'time_start'=>$request['time_start'],
            'time_finish'=>$request['time_finish']
        ];
        $test=$this->testRepository->create($data);
            if($test){
                    $userTest=UserTest::create(['user_id' => $user->id, 'test_id' => $test->id, 'role' => 1]);
                };
            $responseData = [
                'test_id' => $test->id,
                'name' => $test->name,
                'note' => $test->note,
            ];
            return $this->sendResponse($responseData, 'Create test successfully');
}}
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
            $answers = AnswerTest::where('exam_id','=', $exam->id)->get();
            
            if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,0);
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
                $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, 0);
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
public function update($request){
       
    $data=$request->all();
    
        $test=Test::find($data['id']);
        $data = Arr::except($data, ['id']);
        $test->update($data);
        $test->save();
        return $this->sendResponse($test, "Update Sucessfully");
}
public function listQuestionUpdate($idTest, $current_page){
        // dd($status);
        // dd($idTest);
        $page = $this->testRepository->findWhere(['id' => $idTest], ['total_page'], "")->first();
        // dd($pages);
       
        $startIndex = 0;
        $dataQuestions = [];
        $dataQuestions = [
            'questions' => [],
            'pages' => [],
        ];
        if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,1);
                $startIndex = $listQuestions['startIndex'];
           
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null; 
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
            // dd($listQuestions);
             $startIndex = $listQuestions['startIndex'];
           
             foreach ($listQuestions['questions'] as $question) {
            $dataQuestion = null; 
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
public function listQuestionHistory($idTest, $current_page){
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
        if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,1);
                $startIndex = $listQuestions['startIndex'];
           
                foreach ($listQuestions['questions'] as $question) {
                $dataQuestion = null; 
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
            // dd($listQuestions);
             $startIndex = $listQuestions['startIndex'];
           
             foreach ($listQuestions['questions'] as $question) {
            $dataQuestion = null; 
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
public function nummericalQuestionUpdate($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $list = [];
       for($i=1;$i<=$test->total_page;$i++){
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  1);
            foreach($listQuestionPage['questions'] as $question){
                $item = [];
                if($question->type==2){
                    if($question->result_id!=null){
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                    }else{  
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                    };
                }else{
                    if($question->contentResult!=null){
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                    }else{  
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                    };
                }
                
                $list[] = $item;
            };
       }
        $data = ['data' => $list];
        return $this->sendResponse($data, "Succesfully");
}
public function nummericalQuestionDo($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $list = [];
        if($request->exam_id==null){
             return $this->sendError(null, "Error");
        };
       
         $exam=Exam::find($request->exam_id);
        $user=Auth::user();
         $listAnswers = AnswerTest::where('exam_id', '=', $request->exam_id)->get();
        $answers = [];
        foreach($listAnswers as $answer){
            $answers[$answer->question_id] = $answer->answer;
        }
       for($i=1;$i<=$test->total_page;$i++){
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  0);
            foreach($listQuestionPage['questions'] as $question){
               $item = [];
                if($question->type==2){
                        if(array_key_exists($question->question_id, $answers)){
                            if($answers[$question->question_id]){
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                            }else{
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                            }
                        }else{
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                        }
                    
                }else{
                  if(array_key_exists($question->question_id, $answers)){
                            if($answers[$question->question_id]){
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                            }else{
                                 $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
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
                    if($question->contentResult!=null){
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                    }else{  
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                    };
                }
                
                $list[] = $item;
            };
       }
        $data = ['data' => $list];
        return $this->sendResponse($data, "Succesfully");
}
public function show($id){
   
    $test=$this->testRepository->find($id);

    $data=[
        'test'=>$test
    ];
    
    return $this->sendResponse($data, "OK");
}

}
?>