<?php  
namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Repositories\AbstractRepository;

class ClassroomRepository extends AbstractRepository{
    public function model()
    {
       return Classroom::class;
    }
  
    public function list()
    {
        return $this->model->where('status', '=','1')->get();
    }
    public function listUser()
    {
        return $this->model->where('status', '=','1')->get();
    }
  
}

?>