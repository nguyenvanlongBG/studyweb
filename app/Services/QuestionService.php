<?php
namespace App\Services;

use App\Repositories\Question\QuestionRepository;
use Illuminate\Http\Request;

class QuestionService extends BaseService{

private QuestionRepository $questionRepository;
public function __construct(QuestionRepository $questionRepository )
{
    $this->questionRepository=$questionRepository;
}
public function storeImage(Request $request){
$path=$request->file('content')->store('public/images');
return $path;
}
public function questionNormals(){
$questionNormals=$this->questionRepository->findWhere([['scope','=', '0']], ['*'], '');
// dd($questionNormals);
return $questionNormals;
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