<?php  
namespace App\Repositories\ChooseQuestion;

use App\Models\ChooseQuestion;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class ChooseQuestionRepository extends AbstractRepository{
    public function model()
    {
       return ChooseQuestion::class;
    }
  
    public function list()
    {
        return null;
    }
}

?>