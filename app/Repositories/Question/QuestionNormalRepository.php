<?php  
namespace App\Repositories\Question;

use App\Models\QuestionNormal;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class QuestionNormalRepository extends AbstractRepository{
    public function model()
    {
       return QuestionNormal::class;
    }
  
    // public function list()
    // {
    //     return $this->model->all();
    // }
  
  
}

?>