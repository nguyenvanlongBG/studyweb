<?php  
namespace App\Repositories\Answer;

use App\Models\AnswerQuestionTest;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
class AnswerQuesionTestRepository extends AbstractRepository{
    public function model()
    {
       return AnswerQuestionTest::class;
    }
    public function listByIdQuestion($id){
        // dd("Rep");
       return $this->findWhere(['question_id' => $id], $columns = ['*'], $rank="");
    }
    public function list()
    {
        return null;
    }
}

?>