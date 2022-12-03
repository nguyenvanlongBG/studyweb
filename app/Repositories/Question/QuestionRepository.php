<?php  
namespace App\Repositories\Question;

use App\Models\Question;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class QuestionRepository extends AbstractRepository{
    public function model()
    {
       return Question::class;
    }
 public function listDetailQuestion($test_id)
    {
       return  $data=$this->model->join('correct_choose_question_tests', 'correct_choose_question_tests.question_id', '=','questions.id')->where('dependence_id', '=', $test_id)->get()->unique('question_id');
     
    }

  
    // public function list()
    // {
    //     return $this->model->all();
    // }
  
  
}

?>