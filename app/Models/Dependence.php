<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependence extends Model
{
    use HasFactory;
    protected $fillable=[
        'question_id',
        'test_id',
        'subject_id',
        'item_subject_id',
        'point'
    ];
    public function questions(){
        $this->belongsTo(Question::class);
    }
      public function tests(){
        $this->belongsTo(Test::class);
    }
      public function subjects(){
        $this->belongsTo(Subject::class);
    }
      public function itemSubjects(){
        $this->belongsTo(ItemSubject::class);
    }
}
