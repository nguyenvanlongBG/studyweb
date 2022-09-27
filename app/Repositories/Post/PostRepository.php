<?php  
namespace App\Repositories\Post;
use App\Models\Post;
use App\Repositories\AbstractRepository;

class PostRepository extends AbstractRepository{
    public function model()
    {
       return Post::class;
    }
  
    public function list()
    {
        return $this->model->where('status', '=','1')->get();
    }
  
}

?>