<?php 
namespace App\Services;
use App\Repositories\Classroom\ClassroomRepository;
use App\Services\BaseService;
use App\Models\ClassroomUser;
use App\Http\Requests\ClassroomRequest;
use App\Models\RequestClass;
use Illuminate\Http\JsonResponse;

class ClassroomService extends BaseService
{
    private ClassroomRepository $classroomRepository;
    public function __construct(ClassroomRepository $classroomRepository)
    {
        
        $this->classroomRepository=$classroomRepository;
    }
   
    public function createClassroom(ClassroomRequest $request)
    {
        $data=[
            'name'=>$request['name'],
            'level'=> $request['level'],
            'status'=>$request['status']
        ];
        if($this->classroomRepository->create($data)){
             return true;
        }
        return false;
    }
    public function list()
    {
        $classrooms=$this->classroomRepository->list();
        return $classrooms;
    }
    public function approveUser($id){
        $requestClass=RequestClass::find($id);
        $data=[
            'user_id'=>$requestClass->user_id,
            'classroom_id'=>$requestClass->classroom_id
        ];
        ClassroomUser::create($data);
    }
    public function listUser($idClass)
    {
        $users=$this->classroomRepository->listUser($idClass);
        return $users;
    }
}

?>