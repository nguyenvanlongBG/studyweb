<?php  
namespace App\Repositories\Exam;

use App\Models\Exam;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class ExamRepository extends AbstractRepository{
    public function model()
    {
       return Exam::class;
    }
  
    public function list()
    {
        return this->model()->all();
    }
  
  
}

?>