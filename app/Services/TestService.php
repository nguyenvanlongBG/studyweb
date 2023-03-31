<?php
namespace App\Services;
use App\Models\AnswerQuestionTest;
use App\Models\AnswerTest;
use App\Models\Exam;
use App\Models\ItemSubjectQuestion;
use App\Models\PropertyQuestion;
use App\Models\Question;
use App\Models\QuestionBelong;
use App\Models\QuestionDo;
use App\Models\ResultQuestion;
use App\Models\Test;
use App\Models\UserTest;
use App\Repositories\Answer\AnswerNormalRepository;
use App\Repositories\Answer\AnswerQuestionTestRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Question\PropertyQuestionRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Undefined;
use TheSeer\Tokenizer\Exception;

class TestService extends BaseService{
private TestRepository $testRepository;
private QuestionRepository $questionRepository;
private PropertyQuestionRepository $propertyQuestionRepository;
private ExamRepository $examRepository;
private AnswerQuestionTestRepository $answerQuestionTestRepository;// private AnswerQuesionTestRepository $answerQuestionTestRepository;
    private AnswerNormalRepository $answerNormalRepository;
public function __construct(TestRepository $testRepository, QuestionRepository $questionRepository, AnswerQuestionTestRepository $answerQuestionTestRepository, PropertyQuestionRepository $propertyQuestionRepository, ExamRepository $examRepository, AnswerNormalRepository $answerNormalRepository)
{
    $this->testRepository=$testRepository;
    $this->questionRepository=$questionRepository;
    $this->answerQuestionTestRepository=$answerQuestionTestRepository;
    $this->propertyQuestionRepository=$propertyQuestionRepository;
    $this->examRepository=$examRepository;
        $this->answerNormalRepository = $answerNormalRepository;
}
public function list($request){
        $filters=[];
       // Bộ đề của giáo viên
        if( $request->role!=null){
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
        
        return $this->sendResponse($this->testRepository->listByFilter($filters), "Successful");
}
public function create($user, $request){{
        $data=[
            'type'=>$request['type'],
            'subject_id'=>$request['subject_id'],
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
            DB::beginTransaction();
            try {
                $test = $this->testRepository->create($data);
                if ($test) {
                    $userTest = UserTest::create(['user_id' => $user->id, 'test_id' => $test->id, 'role' => 1]);
                }
                ;
                $responseData = [
                    'test_id' => $test->id,
                    'name' => $test->name,
                    'note' => $test->note,
                ];
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                return $this->sendResponse($responseData, 'Create test successfully');
            }
            return $this->sendError(null, 'Error');
}}
public function detail($id){
        $user = Auth::user();
        $test = $this->testRepository->find($id);
        if($user) {
            $userTest = UserTest::where('user_id', '=', $user->id)->where('test_id', '=', $test->id)->get();
            if ($userTest->role == 1 || $userTest == 2) {
                return $this->sendResponse($test, "Sucessfully");
            } 
        }
        return $this->sendResponse($test->makeHidden(['belong_id', 'scope', 'allow_rework', 'mark_option', 'note']), "Sucessfully");
            
    }
    public function import($id,$request){
        $document=$request->document;
        $listQuestion=[];
        $lines=explode('</p>',$document); 
        // dd($lines);
        $isEndContentQuestion=false;
        $contentQuestion='';
        $currentChoice='';
        $explan='';
        $endExplan=true;
        $result=null;
        $maxSpecial=0;
        $temp='';
        $choices=[];
        $begin=true;
        $question=null;
        $listAnswers=[];
        foreach($lines as $line){
            if($line==''){
                continue;
            }
            preg_match('/(Bài|Câu|\d+[.:]\s*[a-zA-Z\s]+)/u',$line,$matches, PREG_OFFSET_CAPTURE);
            $position=count($matches)>=1?$matches[0][1]:-1;
            $checkSameLine=[];
            preg_match_all('/[A-Z]\.[\s\w<]|[A-Z]\.|\b[A-Z]\<\w*\>\./u',$line,$matchesAnswers, PREG_OFFSET_CAPTURE);
            foreach($matchesAnswers[0] as $answer){
                $pos = mb_strlen(substr($line, 0, $answer[1]), 'UTF-8');
                $checkSameLine[]=$pos;
            }

            $detailExplanation=preg_match('/(Lời giải|Câu trả lời|Trả lời)/',$line);
            // THuật toán cắt nhỏ
                // UTF-8 có một số chữ cái mã hóa cần thêm 1 số kí tự nên thêm dấu cách vào để đủ lấy hết
                    $line.=' </p>';
                    $n=mb_strlen($line);
                    // <p> Content </p>
                    $stackOpen=[];
                    $stackClose=[];
                    // $firstChoice=true;
                    $selfClosedTags = ["area",  "base", "br", 
                        "col",   "embed",  "hr",    "img", 
                        "input", "link", "meta", "param", 
                        "source", "track", "wbr"];
                    $i=0;
                    while($i+1<=$n-1){
                            if(count($checkSameLine)>=1){
                                if(mb_substr($line, $i, 1, 'UTF-8')=='<'){
                                    if('a'<=mb_substr($line, $i+1, 1, 'UTF-8')&& mb_substr($line, $i+1, 1, 'UTF-8')<='z'){
                                        $i+=1;
                                        $tagName='';
                                        while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                            $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                            $i+=1;
                                        }
                                        if(in_array($tagName,$selfClosedTags)){
                                            // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');   
                                        }else{
                                            // $temp.='<'.$tag.mb_substr($line, $i, 1, 'UTF-8');
                                            array_push($stackClose, '</'.$tagName.'>');
                                        }
                                        while($i<$n&&mb_substr($line, $i, 1, 'UTF-8')!='>'){
                                            $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                            $i+=1;
                                        }
                                        $i+=1;
                                        if(in_array($tagName,$selfClosedTags)){
                                            $temp.='<'.$tagName.'>';   
                                        }else{
                                            $temp.='<'.$tagName.'>';
                                            array_push($stackOpen, '<'.$tagName.'>');
                                        }
                                    }else{
                                        if(mb_substr($line, $i+1, 1, 'UTF-8')=='/'){
                                            // while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                            //     $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                            //     $i+=1;
                                            // }
                                            // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');
                                            $tag=array_pop($stackClose);
                                            array_pop($stackOpen);
                                            $temp.=$tag;
                                            $i+=strlen($tag);
                                        }else{
                                            $temp.='<';
                                            $i+=1;
                                        }
                                    }
                                }
                                if($position){
                                    if($i==$position){
                                        if($begin){
                                           $begin=false;
                                        }else{
                                            foreach($stackClose as $closeTag){
                                                $temp.=$closeTag;
                                            }
                                            if(count($choices)){
                                                $choices[count($choices)-1]['content'].=$temp;
                                            }else{
                                                $contentQuestion.=$temp;
                                            }
                                            $question=Question::create(['content'=>$contentQuestion, 'user_id'=>1,'mathML'=>$contentQuestion, 'subject_id'=>1,'type'=>2,'scope'=>1]);
                                            QuestionBelong::create(['question_id'=>$question->id, 'type'=>1,'belong_id'=>$id]);
                                            $listQuestion[]=[
                                                'question'=>['content'=>$contentQuestion, 'question_id'=>count($listQuestion)+1, 'type'=>2],
                                                'choices'=>$choices,
                                                'solutions'=>$result,
                                                'explan'=>$explan
                                            ]; 
                                        }
                                        foreach($choices as $choice){
                                             $listAnswers[]=$choice;
                                        };
                                        $contentQuestion='';
                                        $result=null;
                                        $choices=[];
                                        $temp='';
                                        $explan='';
                                        foreach($stackOpen as $openTag){
                                               $temp.=$openTag;
                                        }
                                        $isEndContentQuestion=false;
                                    }
                                }
                                if(in_array($i, $checkSameLine)){
                                    if(count($stackClose)==0){
                                            if(!$isEndContentQuestion){
                                                $contentQuestion.=$temp;
                                                $question=Question::create(['content'=>$contentQuestion, 'user_id'=>1,'mathML'=>$contentQuestion, 'subject_id'=>1,'type'=>2,'scope'=>1]);
                                                QuestionDo::create(['question_id'=>$question->id, 'type'=>1,'belong_id'=>$id]);
                                                $choices[]=['content'=>'','question_id'=>$question->id];
                                            }else{
                                                if(count($choices)>=1){
                                                    $choices[count($choices)-1]['content'].=$temp;
                                                }
                                                $choices[]=['content'=>'','question_id'=>$question->id];
                                            }
                                            $temp='';
                                            $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }else{
                                        foreach($stackClose as $closeTag){
                                               $temp.=$closeTag;
                                        };
                                        if(!$isEndContentQuestion){
                                            $contentQuestion.=$temp;
                                            $question=Question::create(['content'=>$contentQuestion, 'user_id'=>1,'mathML'=>$contentQuestion, 'subject_id'=>1,'type'=>2,'scope'=>1]);
                                            QuestionDo::create(['question_id'=>$question->id, 'type'=>1,'belong_id'=>$id]);
                                            $choices[]=['content'=>'','question_id'=>$question->id];
                                        }else{
                                            if(count($choices)>=1){
                                                    $choices[count($choices)-1]['content'].=$temp;
                                            }
                                            $choices[]=['content'=>'','question_id'=>$question->id];
                                        }
                                        $temp='';
                                        foreach($stackOpen as $openTag){
                                            $temp.=$openTag;
                                        }
                                        if(count($stackOpen)>$maxSpecial){
                                                $result=count($choices);
                                        }
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8').'.';
                                        $i+=1;
                                    }
                                    if(count($choices)){
                                         $isEndContentQuestion=true;
                                    }
                                    $startLine=false;
                                }else{
                                    $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                }
                            }else{
                                    if($position>-1){
                                        if(mb_substr($line, $i, 1, 'UTF-8')=='<'){
                                            if('a'<=mb_substr($line, $i+1, 1, 'UTF-8')&& mb_substr($line, $i+1, 1, 'UTF-8')<='z'){
                                                $i+=1;
                                                $tagName='';
                                                while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                                    $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                                    $i+=1;
                                                }
                                                if(in_array($tagName,$selfClosedTags)){
                                                    // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');   
                                                }else{
                                                    // $temp.='<'.$tag.mb_substr($line, $i, 1, 'UTF-8');
                                                    array_push($stackClose, '</'.$tagName.'>');
                                                }
                                                while($i<$n-4&&mb_substr($line, $i, 1, 'UTF-8')!='>'){
                                                    $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                                    $i+=1;
                                                }
                                                $i+=1;
                                                if(in_array($tagName,$selfClosedTags)){
                                                    $temp.='<'.$tagName.'>';   
                                                }else{
                                                    $temp.='<'.$tagName.'>';
                                                    array_push($stackOpen, '<'.$tagName.'>');
                                                }
                                            }else{
                                                if(mb_substr($line, $i+1, 1, 'UTF-8')=='/'){
                                                    // while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                                    //     $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                                    //     $i+=1;
                                                    // }
                                                    // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');
                                                    $tag=array_pop($stackClose);
                                                    array_pop($stackOpen);
                                                    $temp.=$tag;
                                                    $i+=strlen($tag);
                                                }else{
                                                    $temp.='<';
                                                }
                                            }
                                    }
                                    if($i>=$position){
                                        if($i==$position){
                                            // dd($currentChoice);
                                            if($begin){
                                                $begin=false;
                                            }else{
                                                foreach($stackClose as $closeTag){
                                                        $temp.=$closeTag;
                                                }
                                                if(count($choices)){
                                                    $choices[count($choices)-1]['content'].=$temp;
                                                }else{
                                                    $contentQuestion.='';
                                                }
                                                $question=Question::create(['content'=>$contentQuestion, 'user_id'=>1,'mathML'=>$contentQuestion, 'subject_id'=>1,'type'=>2,'scope'=>1]);
                                                QuestionDo::create(['question_id'=>$question->id, 'type'=>1,'belong_id'=>$id]);
                                                $listQuestion[]=[
                                                    'question'=>['content'=>$contentQuestion, 'question_id'=>$question->id, 'type'=>2],
                                                    'choices'=>$choices,
                                                    'solutions'=>$result,
                                                    'explan'=>$explan
                                                ]; 
                                                foreach($choices as $choice){
                                                       $listAnswers[]=$choice;
                                                };
                                                $temp='';
                                                foreach($stackOpen as $openTag){
                                                        $temp.=$openTag;
                                                }
                                            }   
                                            $explan='';
                                            $contentQuestion='';
                                            $result=null;
                                            $choices=[];
                                            $isEndContentQuestion=false;
                                        }
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }else{
                                        if($i==3){
                                            if(!$isEndContentQuestion){
                                                $contentQuestion.='';
                                            }else{
                                                if(count($choices)>=1){
                                                    // $currentChoice='A';
                                                    $choices[count($choices)-1]['content'].='';
                                                }
                                            }
                                        }
                                        $startLine=false;
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }
                                }else{
                                    if($detailExplanation>0){
                                          $explan.=$line;
                                          $endExplan=false;
                                    }else{
                                        if(!$endExplan){
                                           $explan.=$line;
                                        }else{
                                            if(!$isEndContentQuestion){
                                               $temp.=$line;
                                            }else{
                                                if(count($choices)>=1){
                                                        $choices[count($choices)-1]['content'].=$line;
                                                }
                                            }
                                        }
                                    }
                                    $i=$n-1;
                                }
                            }
                        
                        $i+=1;
                    }
        }
        if(count($choices)){
            $choices[count($choices)-1]['content'].=$temp;
        }else{
            $contentQuestion=$temp;
        }
        foreach($choices as $choice){
            $listAnswers[]=$choice;
        };  
        AnswerQuestionTest::insert($listAnswers);
        $listQuestion[]=[
                            'question'=>['content'=>$contentQuestion, 'question_id'=>count($listQuestion)+1, 'type'=>2],
                            'choices'=>$choices,
                            'solutions'=>$result,
                            'explan'=>$explan
                        ]; 
        return $this->sendResponse($listQuestion, "Upload Sucessful");
    }
    
    public function importDontHasExxplanation($request){
        $document=$request->document;
        // $text='';
        // for($i=0;$i<mb_strlen($document);$i++){
        //         $text.=mb_substr($document, $i+1, 1, 'UTF-8');
        // };
        $listQuestion=[];
        $lines=explode('</p>',$document); 
        // dd($lines);
        $isEndContentQuestion=false;
        $contentQuestion='';
        $currentChoice='';
        $result=null;
        $maxSpecial=0;
        $temp='';
        $choices=[];
        $begin=true;
        foreach($lines as $line){
            if($line==''){
                continue;
            }
            preg_match('/(Bài|Câu|\d+[.:]\s*[a-zA-Z\s]+)/u',$line,$matches, PREG_OFFSET_CAPTURE);
            $position=count($matches)>=1?$matches[0][1]:-1;
            $checkSameLine=[];
            preg_match_all('/[A-Z]\.[\s\w<]|[A-Z]\.|\b[A-Z]\<\w*\>\./u',$line,$matchesAnswers, PREG_OFFSET_CAPTURE);
            foreach($matchesAnswers[0] as $answer){
                $pos = mb_strlen(substr($line, 0, $answer[1]), 'UTF-8');
                $checkSameLine[]=$pos;
            }
            preg_match('/(Lời giải|Câu trả lời|Trả lời)/u',$line,$matchesExplanation, PREG_OFFSET_CAPTURE);
                // THuật toán cắt nhỏ
                // UTF-8 có một số chữ cái mã hóa cần thêm 1 số kí tự nên thêm dấu cách vào để đủ lấy hết
                    $line.=' </p>';
                    $n=mb_strlen($line);
                    // <p> Content </p>
                    $stackOpen=[];
                    $stackClose=[];
                    // $firstChoice=true;
                    $selfClosedTags = ["area",  "base", "br", 
                        "col",   "embed",  "hr",    "img", 
                        "input", "link", "meta", "param", 
                        "source", "track", "wbr"];
                    $i=3;
                    while($i+1<=$n-4){
                        if(mb_substr($line, $i, 1, 'UTF-8')=='<'){
                            if('a'<=mb_substr($line, $i+1, 1, 'UTF-8')&& mb_substr($line, $i+1, 1, 'UTF-8')<='z'){
                                $i+=1;
                                $tagName='';
                                while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                    $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                    $i+=1;
                                }
                                if(in_array($tagName,$selfClosedTags)){
                                    // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');   
                                }else{
                                    // $temp.='<'.$tag.mb_substr($line, $i, 1, 'UTF-8');
                                    array_push($stackClose, '</'.$tagName.'>');
                                }
                                while($i<$n-4&&mb_substr($line, $i, 1, 'UTF-8')!='>'){
                                    $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                    $i+=1;
                                }
                                if(in_array($tagName,$selfClosedTags)){
                                    $temp.='<'.$tagName.'>';   
                                }else{
                                    $temp.='<'.$tagName.'>';
                                    array_push($stackOpen, '<'.$tagName.'>');
                                }
                            }else{
                                if(mb_substr($line, $i+1, 1, 'UTF-8')=='/'){
                                    // while($i<$n-4&&'a'<=mb_substr($line, $i, 1, 'UTF-8')&&'z'>=mb_substr($line, $i, 1, 'UTF-8')){
                                    //     $tagName.=mb_substr($line, $i, 1, 'UTF-8');
                                    //     $i+=1;
                                    // }
                                    // $temp.='<'.$tagName.mb_substr($line, $i, 1, 'UTF-8');
                                    $tag=array_pop($stackClose);
                                    array_pop($stackOpen);
                                    $temp.=$tag;
                                    $i+=strlen($tag);
                                }else{
                                    $temp.='<';
                                }
                            }
                        }else{
                            if(count($checkSameLine)>=1){
                                if($position){
                                    if($i==$position){
                                        if($begin){
                                           $begin=false;
                                        }else{
                                            foreach($stackClose as $closeTag){
                                                $temp.=$closeTag;
                                            }
                                            if(count($choices)){
                                                $choices[count($choices)-1]['content'].=$temp.'</p>';
                                            }else{
                                                $contentQuestion.=$temp.'</p>';
                                            }
                                            $listQuestion[]=[
                                                'question'=>['content'=>$contentQuestion, 'question_id'=>count($listQuestion)+1, 'type'=>2],
                                                'choices'=>$choices,
                                                'result'=>$result
                                            ]; 
                                        }
                                        $contentQuestion='';
                                        $result=null;
                                        $choices=[];
                                        $temp='';
                                        foreach($stackOpen as $openTag){
                                               $temp.=$openTag;
                                        }
                                        $isEndContentQuestion=false;
                                    }
                                }
                                if(in_array($i, $checkSameLine)){
                                    if(count($stackClose)==0){
                                            if(!$isEndContentQuestion){
                                                $contentQuestion.='<p>'.$temp.'</p>';
                                                $choices[]=['content'=>'','question_id'=>1];
                                            }else{
                                                if(count($choices)>=1){
                                                    $choices[count($choices)-1]['content'].='<p>'.$temp.'</p>';
                                                }
                                                $choices[]=['content'=>'','question_id'=>1];
                                            }
                                            $temp='';
                                            $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }else{
                                        foreach($stackClose as $closeTag){
                                               $temp.=$closeTag;
                                        };
                                        if(!$isEndContentQuestion){
                                            $contentQuestion.='<p>'.$temp.'</p>';
                                            $choices[]=['content'=>'','question_id'=>1];
                                        }else{
                                            if(count($choices)>=1){
                                                    $choices[count($choices)-1]['content'].='<p>'.$temp.'</p>';
                                            }
                                            $choices[]=['content'=>'','question_id'=>1];
                                        }
                                        $temp='';
                                        foreach($stackOpen as $openTag){
                                            $temp.=$openTag;
                                        }
                                        if(count($stackOpen)>$maxSpecial){
                                                $result=count($choices);
                                        }
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8').'.';
                                        $i+=1;
                                    }
                                    if(count($choices)){
                                         $isEndContentQuestion=true;
                                    }
                                    $startLine=false;
                                }else{
                                    $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                }
                            }else{
                                if($position>-1){
                                    if($i>=$position){
                                        if($i==$position){
                                            // dd($currentChoice);
                                            if($begin){
                                                $begin=false;
                                            }else{
                                                foreach($stackClose as $closeTag){
                                                        $temp.=$closeTag;
                                                }
                                                if(count($choices)){
                                                    $choices[count($choices)-1]['content'].='<p>'.$temp.'</p>';
                                                }else{
                                                    $contentQuestion.='</p>';
                                                }
                                                $listQuestion[]=[
                                                    'question'=>['content'=>$contentQuestion, 'question_id'=>count($listQuestion)+1, 'type'=>2],
                                                    'choices'=>$choices,
                                                    'result'=>$result
                                                ]; 
                                                $temp='';
                                                foreach($stackOpen as $openTag){
                                                        $temp.=$openTag;
                                                }
                                            }   
                                            $contentQuestion='';
                                            $result=null;
                                            $choices=[];
                                            $isEndContentQuestion=false;
                                        }
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }else{
                                        if($i==3){
                                            if(!$isEndContentQuestion){
                                                $contentQuestion.='<p>';
                                            }else{
                                                if(count($choices)>=1){
                                                    // $currentChoice='A';
                                                    $choices[count($choices)-1]['content'].='<p>';
                                                }
                                            }
                                        }
                                        $startLine=false;
                                        $temp.=mb_substr($line, $i, 1, 'UTF-8');
                                    }
                                }else{
                                    if(!$isEndContentQuestion){
                                        $contentQuestion.=$line;
                                    }else{
                                        if(count($choices)>=1){
                                                $choices[count($choices)-1]['content'].=$line;
                                        }
                                    }
                                }
                            }
                        }
                        $i+=1;
                    }
        }
        if(count($choices)){
            $choices[count($choices)-1]['content'].='<p>'.$temp.'</p>';
        }else{
            $contentQuestion='<p>'.$temp.'</p>';
        }
        $listQuestion[]=[
                            'question'=>['content'=>$contentQuestion, 'question_id'=>count($listQuestion)+1, 'type'=>2],
                            'choices'=>$choices,
                            'result'=>$result
                        ]; 
        return $this->sendResponse($listQuestion, "Upload Sucessful");
    }
    
public function update($request){
       
    $data=$request->all();
        DB::beginTransaction();
        try {
            $test = Test::find($data['id']);
            $data = Arr::except($data, ['id']);
            $test->update($data);
            $test->save();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
        ;
        if($test){
           return $this->sendResponse($test, "Update Sucessfully");
        }
        return $this->sendError(null, "Error");
}
public function listQuestionUpdate($idTest, $current_page){
        $page = $this->testRepository->findWhere(['id' => $idTest], ['total_page'], "")->first();
        $user=Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $idTest])->first();
        $startIndex = 0;
        $dataQuestions = [];
        $dataQuestions = [
            'questions' => [],
            'pages' => [],
            'isOwner'=>true
        ];
        if($userTest->role==1){
            if($current_page==null){
                $listQuestions = $this->questionRepository->findByIdTest($idTest, 1,1);
                $startIndex = $listQuestions['startIndex'];
                foreach ($listQuestions['questions'] as $question) {
                    $dataQuestion = null; 
                    $dataQuestion['items'] = ItemSubjectQuestion::leftJoin('item_subjects', 'item_subjects.id', '=', 'item_subject_questions.id')->select('item_subject_questions.item_subject_id as id', 'item_subjects.name as name')->where('item_subject_questions.question_id', '=', $question['question_id'])->get();
                    if($question['type']==2){
                        $answers = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                        $dataQuestion['question']=$question;
                        $dataQuestion['answers']=$answers;
                        
                        // dd($dataQuestion);
                    }else{
                        $answers = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                        $dataQuestion['question']=$question;
                        $dataQuestion['answers']=$answers;
                    }
                    if($question['scope']==0){
                       $discusses=$this->answerNormalRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");;
                       $dataQuestion['discusses']=$discusses?$discusses:[];
                    }
                    $solutions=[];
                    $solutions=ResultQuestion::where('question_id', $question['question_id'])->select(['answer_question_test_id'])->get();
                    $dataQuestion['solutions']=$solutions?$solutions:[];
                    $dataQuestions['questions'][]=$dataQuestion;
                }
            }else{
                $listQuestions = $this->questionRepository->findByIdTest($idTest, $current_page, 1);
                // dd($listQuestions);
                $startIndex = $listQuestions['startIndex'];
            
                foreach ($listQuestions['questions'] as $question) {
                    $dataQuestion = null; 
                    $dataQuestion['items'] = ItemSubjectQuestion::leftJoin('item_subjects', 'item_subjects.id', '=', 'item_subject_questions.item_subject_id')->select('item_subject_questions.item_subject_id as id', 'item_subjects.name as name')->where('item_subject_questions.question_id', '=', $question['question_id'])->get();
                    $answers = $this->answerQuestionTestRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");
                    $dataQuestion['question']=$question;
                    $dataQuestion['answers']=$answers;
                    if($question['scope']==0){
                       $discusses=$this->answerNormalRepository->findWhere(['question_id' => $question['question_id']], ['*'], "");;
                       $dataQuestion['discusses']=$discusses?$discusses:[];
                    }
                    $solutions=ResultQuestion::where('question_id', $question['question_id'])->pluck('answer_question_test_id')->toArray();
                    $dataQuestion['solutions']=$solutions?$solutions:[];
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
public function nummericalQuestionUpdate($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $list = [];
        $listComplete=ResultQuestion::leftJoin('answer_question_tests','answer_question_tests.question_id','=','result_questions.answer_question_test_id')->where('answer_question_tests.content','<>',null)->pluck('result_questions.question_id')->toArray();
       for($i=1;$i<=$test->total_page;$i++){
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  1);
            foreach($listQuestionPage['questions'] as $question){
                $item = [];
                if(in_array( $question->question_id, $listComplete)){
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                }else{  
                      $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                };
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

public function nummericalQuestionMark($idTest, $request){
        $test = $this->testRepository->find($idTest);
        $data = [];
        $list = [];
        if($request->exam_id==null){
             return $this->sendError(null, "Error");
        };
        $exam=Exam::find($request->exam_id);
        $user=Auth::user();
        $userTest = UserTest::where(['user_id' => $user->id, 'test_id' => $idTest])->first();
        if($userTest->role==0){
             return $this->sendError(null, "Error");
        };
        $listAnswers = AnswerTest::where('exam_id', '=', $request->exam_id)->where('point','<>', null)->get();
        $answers = [];
        foreach($listAnswers as $answer){
            $answers[$answer->question_id] = $answer->answer;
        }
        for($i=1;$i<=$test->total_page;$i++){   
            $listQuestionPage = $this->questionRepository->findByIdTest($idTest, $i,  1);
            foreach($listQuestionPage['questions'] as $question){
                $item = [];
                if(array_key_exists($question->question_id, $answers)){
                    
                     $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 1];
                }else{
                     $item = ['id' => $question->question_id, 'page' => $question->page, 'index' => $question->index, 'type' => 0];
                }
                $list[] = $item;
            };
       }
        $data = ['data' => $list];
        return $this->sendResponse($data, "Succesfully");
}

public function reportTest($id, $request){
        $test = $this->testRepository->find($id);
        $countExams = Exam::where('test_id', '=', $test->id)->where('type', '=', 1)->count('*');
        $data = ['correct'=>[],'fail'=>[],'dont'=>[]];
        $index = 0;
        $columnsName = [];
        for ($i = 1; $i <= $test->total_page; $i++) {
            $query=Question::leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $id)->leftJoin('answer_question_tests','property_questions.result_id', '=', 'answer_question_tests.id' )->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id','answer_question_tests.content As contentResult' );
            $query=DB::table('questions')->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $test->id)->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id' );
            $questions=$query->where('page','=', $i)->orderBy('index')->get()->unique('question_id');
            foreach($questions as $question){
                $index+=1;
                $columnsName[] = "Câu " . $index;
                $data['correct'][$question->question_id] = 0;
                $data['fail'][$question->question_id] = 0;
                $data['dont'][$question->question_id] = 0;
                $answers = DB::table('answer_tests')->where('question_id', '=', $question->question_id)->join('exams', 'exams.id', '=', 'answer_tests.exam_id')->select('answer_tests.*', 'exams.is_marked as is_marked', 'exams.type as type')->where('type','=',1)->where('answer_tests.point','<>',null);
                $reportExam=$answers->select(DB::raw('count(exams.id) as count'), DB::raw('SUM(answer_tests.point) as point'))->get();
                $data['correct'][$question->question_id] +=  number_format($reportExam[0]->point,2);
                $data['fail'][$question->question_id] +=  number_format($reportExam[0]->count*$question->point- $reportExam[0]->point,2);
                $data['dont'][$question->question_id] +=  number_format($countExams*$question->point-$reportExam[0]->count*$question->point,2);
            }
        }
        $score = [];
        $scoreSpectrum = [];
        $exams = Exam::where('test_id', '=', $test->id)->where('point', '<>', null)->get();
        foreach($exams as $exam){
            if(!in_array( $exam->point, $scoreSpectrum)){
                   $scoreSpectrum[] = $exam->point;
            }
            if(array_key_exists($exam->point,$score)){
                $score[$exam->point]+=1;
            }else{
                $score[$exam->point] = 1;
            }
        }
        ;
        sort($scoreSpectrum);
        $seriesSpectrum = [];
        $scoreName=[];
        foreach($scoreSpectrum as $point){
            $scoreName[] = $point . " điểm";
            $seriesSpectrum[] = $score[$point];
        }
        ;
        $dataSend=['horizontal'=>['columns_bar'=>$columnsName,'scoreName'=>$scoreName], 'series'=>['series_bar'=>[['name'=>"Làm đúng",'data'=>array_values($data['correct'])],['name'=>'Làm sai', 'data'=>array_values($data['fail'])], ['name'=>'Chưa làm', 'data'=>array_values($data['dont'])]],'seriesScore'=>$seriesSpectrum]];
        return $this->sendResponse($dataSend, "Successfully");
}
}
?>