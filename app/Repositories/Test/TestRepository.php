<?php  
namespace App\Repositories\Test;

use App\Models\Test;
use App\Repositories\AbstractRepository;

class TestRepository extends AbstractRepository{
    public function model()
    {
       return Test::class;
    }
  
    public function list()
    {
        return $this->model->all();
    }
    public function listUser($idClass)
    {
       
        
    }
       public function listByFilter($filters)
    {
        // dd($filters);
        if($filters!=null){
       $data=$this->model->join('user_tests', 'user_tests.test_id', '=','tests.id')->where($filters)->get()->unique('test_id');
    }else{
        $data=$this->model->join('user_tests', 'user_tests.test_id', '=','tests.id')->get()->unique('test_id');
    }
        return $data;
    }
   
  
}

?>