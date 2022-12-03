<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubjectQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'item_subject_id'
    ];
    public function questions(){
        $this->belongsTo(Question::class);
    }
    public function itemSubjects(){
        $this->belongsTo(ItemSubject::class);
    }
}
