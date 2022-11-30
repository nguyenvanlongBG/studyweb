<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCompetition extends Model
{
    use HasFactory;
    protected $fillable=[
        'group_id',
        'test_id',
        'point'
    ];
    public function groups(){
       return $this->hasOne(Group::class);
    }
        public function tests(){
       return $this->belongsTo(Test::class);
    }
}
