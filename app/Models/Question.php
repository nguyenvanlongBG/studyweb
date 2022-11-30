<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'content',
        'user_id',
        'latex',
        'dependence_id',
        'scope'
    ];
         public function users(){
        $this->belongsTo(User::class);
    }
     
}
