<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalueAnswerNormal extends Model
{
    use HasFactory;
     protected $fillable=[
        'user_id',
        'answer_normal_id',
         'evalue',
    ];
    public function users(){
        $this->belongsTo(User::Class);
    }
      public function answerNormals(){
        $this->belongsTo(AnswerNormal::Class);
    }
}
