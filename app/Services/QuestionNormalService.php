<?php
namespace App\Services;

use App\Repositories\Question\QuestionNormalRepository;
use Illuminate\Http\Request;

class QuestionNormalService extends BaseService{

private QuestionNormalRepository $questionNormalRepository;
public function __construct(QuestionNormalRepository $questionNormalRepository )
{
    $this->questionNormalRepository=$questionNormalRepository;
}
public function storeImage(Request $request){
$path=$request->file('content')->store('public/images');
return $path;
}
public function list(){
$questionNormals=$this->questionNormalRepository->all();
return $questionNormals;
}
public function create($request){
    $path=$this->storeImage($request);
    $data=[
        'content'=>$path,
        'user_id'=>$request['user_id']
    ];
    
    $this->questionNormalRepository->create($data);
}
public function update(){
    
}
public function delete(){
    
}
}
?>