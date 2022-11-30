<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChooseQuestionTest extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_id',
        'content'
    ];
public function questionTests(){
   return $this->belongsTo(Question::class);
}
}
