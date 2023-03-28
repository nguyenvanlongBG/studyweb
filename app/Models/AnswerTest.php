<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'answer',
        // Choose Question it is ID Choose, fill Question it is Value, Essay it is result
        'question_id',
        'exam_id',
        'point'
    ];
    public function questions(){
       return $this->belongsTo(Question::class);
    }
    public function exams():BelongsTo{
       return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
