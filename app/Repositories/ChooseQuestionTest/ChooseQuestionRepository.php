<?php  
namespace App\Repositories\ChooseQuestionTest;

use App\Models\ChooseQuestionTest;
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
}

?>