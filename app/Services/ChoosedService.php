<?php
namespace App\Services;

use App\Repositories\Choosed\ChoosedRepository;
use Illuminate\Http\Request;

class ChoosedService extends BaseService{
private ChoosedRepository $choosedRepository;
public function __construct(ChoosedRepository $choosedRepository )
{
    $this->choosedRepository=$choosedRepository;
}
public function list(){

}
public function create($request){
    
$data=[
    'choosed'=>$request['choosed'],
    'question_test_id'=>$request['question_test_id'],
    'exam_id'=>$request['exam_id']
];

if($this->choosedRepository->create($data)){
    return true;
}
return false;
}
}
?>