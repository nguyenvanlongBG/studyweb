<?php
namespace App\Repositories\Question;
use App\Models\PropertyQuestion;
use App\Repositories\AbstractRepository;

class PropertyQuestionRepository extends AbstractRepository
{
public function model(){
        return PropertyQuestion::class;
}

}


?>