<?php  
namespace App\Repositories\CorrectAnswer;

use App\Models\CorrectChooseQuestionTest;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class CorrectChooseQuestionTestRepository extends AbstractRepository{
    public function model()
    {
       return CorrectChooseQuestionTest::class;
    }
  
    public function list()
    {
        return null;
    }
  
  
}

?>