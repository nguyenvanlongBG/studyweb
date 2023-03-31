<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultQuestion extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_id',
        'answer_question_test_id'
    ];
}
