<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubject extends Model
{
    use HasFactory;
    protected $fillable=[
            'name',
            'subject_id'
    ];
    public function subjects(){
       return $this->belongsTo(Subject::class);
    }
      public function questionTests(){
       return $this->hasMany(QuestionTest::class);
    }
     public function statistical(){
       return $this->belongsToMany(Statistical::class);
    }
}
