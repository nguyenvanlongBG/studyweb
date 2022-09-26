<?php  
namespace App\Repositories\Post;
use App\Models\Post;
class PostRepository{
    public function model()
    {
       return Post::class;
    }
    public function list()
    {
        
        return $this->model()::all();
    }
}

?>