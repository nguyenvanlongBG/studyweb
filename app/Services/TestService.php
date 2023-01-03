<?php
namespace App\Services;

use App\Models\AnswerQuestionTest;

use App\Models\PropertyQuestion;
use App\Models\Question;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Question\PropertyQuestionRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionRepository $questionRepository;
private PropertyQuestionRepository $propertyQuestionRepository;
    private AnswerQuestionTestRepository $answerQuestionTestRepository;// private AnswerQuesionTestRepository $answerQuestionTestRepository;
public function __construct(TestRepository $testRepository, QuestionRepository $questionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository, PropertyQuestionRepository $propertyQuestionRepository )
{
    $this->testRepository=$testRepository;
    $this->questionRepository=$questionRepository;
    $this->answerQuestionTestRepository=$answerQuestionTestRepository;
    $this->propertyQuestionRepository=$propertyQuestionRepository;
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
public function listQuestionDo($idTest, $request){
        // dd($status);
        // dd($idTest);
        $page = $this->testRepository->findWhere(['id' => $idTest], ['total_page'], "")->first();
        // dd($pages);
        $pages= [
            'total_page'=> $page->total_page,
            'current_page'=> $request->current_page
        ];
       
        $dataQuestions = [];
        $dataQuestions['pages'] = $pages;
        if($request->current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1, ['*']);
                foreach ($listQuestions as $question) {
                $dataQuestion = null; 
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                    // dd($dataQuestion);
                }else{
                    if($question['type']==1){
                        $dataQuestion['question']=$question;
                    }else{
                    $dataQuestion = null;
                    }
                }
               $dataQuestions['questions'][]=$dataQuestion;
            }
        }else{
             $listQuestions = $this->questionRepository->findByIdTest($idTest, $request->current_page, ['*']);
             foreach ($listQuestions['questions'] as $question) {
            $dataQuestion = null; 
                if($question['type']==2){
                    $choices = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['choices']=$choices;
                    // dd($dataQuestion);
                }else{
                    if($question['type']==1){
                        $dataQuestion['question']=$question;
                    }else{
                    $dataQuestion = null;
                    }
                }

               $dataQuestions['questions'][]=$dataQuestion;
            }
        }
            return $dataQuestions;
    
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
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,['*']);
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
             $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, ['*']);
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
            'total_page'=> $page->total_page,
            'current_page'=> $current_page,
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
            $select = ['question_id', 'page', 'index'];
           
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  $select);
            foreach($listQuestionPage['questions'] as $question){
                $item = [];
               
                if($question->result_id!=null){
                     $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                }else{  
                    $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                };
                $list[] = $item;
            };
          
       }
        $data = ['data' => $list];
        return $data;
}
public function nummericalQuestionDo($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $index = 0;
       for($i=1;$i<=$test->total_page;$i++){
            $select = ['question_id', 'page', 'index'];
            $list = [];
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  $select);
            foreach($listQuestionPage as $question){
                
                $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                $list[] = $item;
            };
            if($i==1){
                $index = 1;
                 $data[] = ['indexStart'=>$index, 'data'=>$list];
            }else{  
                $data[] = ['indexStart'=> $index, 'data'=>$list];
            }
            $index += count($list);
       }
        return $data;
}
public function nummericalQuestionHistory($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $index = 0;
       for($i=1;$i<=$test->total_page;$i++){
            $select = ['question_id', 'page', 'index'];
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  $select);
            if($i==1){
                $index = 1;
                 $data[] = ['indexStart'=>$index, 'data'=>$listQuestionPage];
            }else{  
                $data[] = ['indexStart'=> $index, 'data'=>$listQuestionPage];
            }
            $index += count($listQuestionPage);
       }
        return $data;
}
public function create(Request $request){
$data=[
    'name'=>$request['name'],
    'access'=>$request['access'],
    'status'=>$request['status'],
    'time_start'=>$request['time_start'],
    'time_end'=>$request['time_end'],
];
if($this->testRepository->create($data)){
    return true;
}
return false;
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