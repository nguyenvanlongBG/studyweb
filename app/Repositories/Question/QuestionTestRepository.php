<?php  
namespace App\Repositories\Question;

use App\Models\QuestionTest;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class QuestionTestRepository extends AbstractRepository{
    public function model()
    {
       return QuestionTest::class;
    }
  
    public function list()
    {
        return null;
    }
    
  
  
}

?>