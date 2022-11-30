<?php 
namespace App\Services;
use App\Http\Requests\GroupRequest;
use App\Services\BaseService;
use App\Models\GroupUser;
use App\Repositories\Group\GroupRepository;
use App\Models\RequestClass;
use Illuminate\Http\JsonResponse;

class GroupService extends BaseService
{
    private GroupRepository $groupRepository;
    public function __construct(GroupRepository $groupRepository)
    {
        
        $this->groupRepository=$groupRepository;
    }
   
    public function createGroup(GroupRequest $request)
    {
        $data=[
            'name'=>$request['name'],
            'level'=> $request['level'],
            'status'=>$request['status']
        ];
        if($this->groupRepository->create($data)){
             return true;
        }
        return false;
    }
    public function list()
    {
        $groups=$this->groupRepository->list();
        return $groups;
    }
    public function approveUser($id){
        $requestGroup=GroupRequest::find($id);
        $data=[
            'user_id'=>$requestGroup->user_id,
            'classroom_id'=>$requestGroup->group_id
        ];
        GroupUser::create($data);
    }
    public function listUser($idGroup)
    {
        $users=$this->groupRepository->listUser($idGroup);
        return $users;
    }
}

?>