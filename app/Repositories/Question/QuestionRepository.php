<?php  
namespace App\Repositories\Question;

use App\Models\Question;
use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuestionRepository extends AbstractRepository{
    public function model()
    {
       return Question::class;
    }
 public function listDetailQuestion($test_id)
    {
       return  $data=$this->model->join('correct_choose_question_tests', 'correct_choose_question_tests.question_id', '=','questions.id')->where('dependence_id', '=', $test_id)->get()->unique('question_id');
     
    }
     /**
     * @param $id
     * @param $filters, $itemsPerPage
     * @return LengthAwarePaginator
     */
public function findByIdTest($id, $page, $type){
    $query=$this->model->leftJoin('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('property_questions.dependence_id','=', $id)->leftJoin('answer_question_tests','property_questions.result_id', '=', 'answer_question_tests.id' )->select('questions.*', 'property_questions.question_id', 'property_questions.point', 'property_questions.page', 'property_questions.index', 'property_questions.dependence_id', 'property_questions.result_id','answer_question_tests.content As contentResult' );
    $data = [];
    $questions=$query->where('page','=', $page)->orderBy('index')->get()->unique('question_id');
    $startIndex =  $this->model->join('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('dependence_id','=', $id)->where('page', '<', $page)->count()+1;
    $data ['startIndex']=$startIndex;
    if($type==0){
      $data['questions']=$questions->makeHidden(['result_id', 'contentResult']);
    }else{
      $data['questions']=$questions;
    }
   
    return $data;
}
  public function findByIdTestUpdate($id, $page, $select){
   
      // dd($this->model->join('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('dependence_id', '=', $id)->get()->unique('question_id'));
      if($select!=null){
           return $this->model->join('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('dependence_id','=', $id)->where('page','=', $page)->select($select)->orderBy('index')->get();
    }else{
           return $this->model->join('property_questions', 'property_questions.question_id', '=', 'questions.id')->where('dependence_id','=', $id)->where('page','=', $page)->orderBy('index')->get();
    }
}
  
}

?>