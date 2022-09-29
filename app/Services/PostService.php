<?php 
namespace App\Services;
use App\Repositories\Post\PostRepository;
use App\Http\Requests\PostRequest;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class PostService extends BaseService
{
    private PostRepository $postRepository;
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository=$postRepository;
    }
    public function storeImage(PostRequest $request){
        $path=$request->file('content')->store('public/images');
        return $path;
      }
    public function createPost(PostRequest $request)
    {
       
        $path=$this->storeImage($request);
        $data=[
            'user_id'=>$request['user_id'],
            'content'=> $path,
            'approve'=>$request['approve'],
            'status'=>'0'
        ];
        if($this->postRepository->create($data)){
             return true;
        }
        return false;
    }
    public function list()
    {
        
        $posts=$this->postRepository->list();
       
        return $posts;
    }
    public function approvePost($id){
        // dd($id);
        $data=[
            'status'=>'1'
        ];
        if( $this->postRepository->update($id,$data)){
            return "true";
        };
        return "false";
    }
}

?>