<?php 
namespace App\Services;
use App\Repositories\PostRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class PostService extends BaseService
{
    public function __construct(PostRepository $postRepository)
    {
        dd('Construct Post Service');
        $this->postRepository=$postRepository;
    }
    public function createPost($request):JsonResponse
    {
         return $this->sendError(null, __('admin.message.error'));
    }
    public function list()
    {
        dd("OK");
        $posts=$this->postRepository->list();
         return null;
    }
}

?>