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
}

?>