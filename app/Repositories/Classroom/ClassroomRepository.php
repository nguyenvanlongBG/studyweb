<?php  
namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Models\ClassroomUser;
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
    public function listUser($idClass)
    {
        $users=ClassroomUser::where('classroom_id', '=',$idClass)->join('users', 'users.id','=','user_id')->get();
        return $users;
    }
  
}

?>