<?php
namespace App\Repositories\Answer;
use App\Models\AnswerQuestionTest;
use App\Repositories\AbstractRepository;
class AnswerQuestionTestRepository extends AbstractRepository{

	/**
	 * @return mixed
	 */
	public function model() {
        return AnswerQuestionTest::class;
	}
    
}
?>