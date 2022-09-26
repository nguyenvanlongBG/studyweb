<?php  
namespace App\Repositories;
class PostRepository{
    protected function model()
    {
       return Post::class;
    }
    public function list()
    {
        return $this->model->get();
    }
}

?>