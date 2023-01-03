<?php 
namespace App\Services;
use App\Http\Requests\PostRequest;
use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use App\Models\RequestClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserService extends BaseService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository=$userRepository;
    }
    public function storeImage(){
      
        return null;
      }
      public function create($data){
        return $this->userRepository->create($data);
      }
    public function createRequest(Request $request){
       
        $data=[
            'user_id'=>$request['user_id'],
            'classroom_id'=>$request['classroom_id']
        ];
        
        \App\Models\Request::create($data);
    }
    
   
}

?>