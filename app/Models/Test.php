<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'type',
        'scope',
        'fee',
        'point',
        'candidates',
        'reward_init',    
        'time_start',
        'time_end'
    ];
    public function groups(){ 
       return  $this->belongsToMany(Group::class) ;
    }
     public function users(){ 
       return  $this->hasMany(User::class,'user_tests') ;
    }
     public function questionTests(){ 
       return  $this->hasMany(Question::class) ;
    }
     
}

