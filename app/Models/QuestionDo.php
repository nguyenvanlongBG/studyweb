<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionDo extends Model
{
    use HasFactory;
    protected $fillable=[
      'type', // Belong Test or Big Question
      'question_id',
      'belong_id', // ID Test or Big Question
      'point',
      'index'
    ];
}
