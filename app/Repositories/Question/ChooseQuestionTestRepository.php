<?php  
namespace App\Repositories\Question;

use App\Models\ChooseQuestionTest;
use App\Models\CorrectChooseQuestionTest;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class ChooseQuestionTestRepository extends AbstractRepository{
    public function model()
    {
       return ChooseQuestionTest::class;
    }
  
    public function list()
    {
        return null;
    }
    public function chooseAndResult($idQuestion){
        if(CorrectChooseQuestionTest::find())
        $data=$this->model->where('question_id','=',$idQuestion )->get()->unique('test_id');

        return $data;
    }
}

?>