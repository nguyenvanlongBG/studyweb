<?php  
namespace App\Repositories\Answer;

use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;
use App\Models\AnswerQuestionTest;
use App\Models\QuestionTest;
class AnswerQuestionTestRepository extends AbstractRepository{
    public function model()
    {
       return AnswerQuestionTest::class;
    }
    public function getAnswersByIdQuestion($id){
        // dd("Rep");
       return $this->findWhere(['question_test_id' => $id], $columns = ['*'], $rank="");
    }
    public function list()
    {
        return null;
    }
}

?>