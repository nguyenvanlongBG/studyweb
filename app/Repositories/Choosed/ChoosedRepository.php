<?php  
namespace App\Repositories\Choosed;
use App\Models\AnswerQuestionTest;
use App\Models\Choosed;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class ChoosedRepository extends AbstractRepository{
    public function model()
    {
       return AnswerQuestionTest::class;
    }
  
    public function list()
    {
        return null;
    }
}

?>