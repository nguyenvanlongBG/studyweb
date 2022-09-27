<?php 
namespace App\Services;
use App\Repositories\Post\PostRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class PostService extends BaseService
{
    private PostRepository $postRepository;
    public function __construct(PostRepository $postRepository)
    {
        
        $this->postRepository=$postRepository;
    }
    public function createPost($request):JsonResponse
    {
         return $this->sendError(null, __('admin.message.error'));
    }
    public function list()
    {
        
        $posts=$this->postRepository->list();
       
        return $posts;
    }
    public function approvePost($id){
        // dd($id);
        $data=[
            'content'=>'Update',
            'status'=>'1'
        ];
        if( $this->postRepository->update($id,$data)){
            return "true";
        };
        return "false";
        // dd( $this->postRepository->update($id,$data));
    }
}

?>