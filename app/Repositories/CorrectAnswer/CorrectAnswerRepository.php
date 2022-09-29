<?php  
namespace App\Repositories\CorrectAnswer;

use App\Models\CorrectAnswer;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class CorrectAnswerRepository extends AbstractRepository{
    public function model()
    {
       return CorrectAnswer::class;
    }
  
    public function list()
    {
        return null;
    }
  
  
}

?>