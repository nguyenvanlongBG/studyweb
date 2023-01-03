<?php
namespace App\Repositories\Answer;
use App\Models\AnswerNormal;
use App\Repositories\AbstractRepository;
class AnswerNormalRepository extends AbstractRepository{

	/**
	 * @return mixed
	 */
	public function model() {
        return AnswerNormal::class;
	}
    
}
?>