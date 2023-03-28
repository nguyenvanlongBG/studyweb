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
    
  
    public function list($filtersExam, $filterUserName)
    {
        $query=$this->model->where($filtersExam);
        $query->join('users', 'users.id', '=', 'exams.user_id')->select('exams.*', 'users.name as user_name');
        if($filterUserName!=""){
            $query->where('users.name', 'like', '%' . $filterUserName . '%');
        }
        return $query->get();
    }
  
  
}

?>