<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confidential extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'content',
        'approve',
        'incognito'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
     public function comments(){
        $this->hasMany(Comment::class);
    }
}
