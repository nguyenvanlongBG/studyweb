<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choosed extends Model
{
    use HasFactory;
    protected $fillable = [
        'choose_question_id',
        'question_test_id',
        'exam_id',
    ];
    public function exam()
    {
        $this->belongsTo(Exam::class);
    }
    public function questionTest()
    {
        $this->belongsTo(QuestionTest::class);
    }
    public function chooseQuestion()
    {
        $this->belongsTo(ChooseQuestion::class);
    }
}
