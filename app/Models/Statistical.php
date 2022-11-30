<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    use HasFactory;
    protected $fillable=[
        'item_subject_id',
        'corect',
        'wrong',
        'total',
        'user_id'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
     public function itemSubjects(){
        $this->hasOne(ItemSubject::class);
    }
}
