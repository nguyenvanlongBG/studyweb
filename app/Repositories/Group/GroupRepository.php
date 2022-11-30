<?php  
namespace App\Repositories\Group;

use App\Models\Group;
use App\Models\GroupUser;
use App\Repositories\AbstractRepository;

class GroupRepository extends AbstractRepository{
    public function model()
    {
       return Group::class;
    }
  
    public function list()
    {
        return $this->model->where('status', '=','0')->get();
    }
    public function listUser($idGroup)
    {
        $users=GroupUser::where('group_id', '=',$idGroup)->join('users', 'users.id','=','user_id')->get();
        return $users;
    }
  
}

?>