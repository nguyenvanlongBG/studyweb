<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerQuestionTest extends Model
{
    use HasFactory;
    protected $fillable=[
'question_id',
'content',
'user_id',
'fee'
    ];
    public function questions(){
        return $this->belongsTo(Question::class);
    }
        public function users(){
        return $this->belongsTo(User::class);
    }
}
