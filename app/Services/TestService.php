<?php
namespace App\Services;

use App\Repositories\Test\TestRepository;
use Illuminate\Http\Request;

class TestService extends BaseService{
private TestRepository $testRepository;
public function __construct(TestRepository $testRepository )
{
    $this->testRepository=$testRepository;
}
public function list(){

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
}
?>