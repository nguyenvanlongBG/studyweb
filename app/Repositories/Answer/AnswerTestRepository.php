<?php  
namespace App\Repositories\Answer;

use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;
use App\Models\AnswerTest;
use App\Models\QuestionTest;
class AnswerTestRepository extends AbstractRepository{
    public function model()
    {
       return AnswerTest::class;
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