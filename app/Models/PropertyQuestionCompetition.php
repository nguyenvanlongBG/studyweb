<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyQuestionCompetition extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_question_id',
        'suggest',
    ];
    public function propertyQuestions(){
        $this->belongsTo(PropertyQuestion::class);
    }
   
}
