<?php  
namespace App\Repositories\Answer;

use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;
use App\Models\AnswerNormal;
use App\Models\QuestionNormal;
class AnswerNormalRepository extends AbstractRepository{
    public function model()
    {
       return AnswerNormal::class;
    }
    public function getAnswersByIdQuestion($id){
        // dd("Rep");
       return $this->findWhere(['question_normal_id' => $id], $columns = ['*'], $rank="");
    }
    public function list()
    {
        return null;
    }
}

?>