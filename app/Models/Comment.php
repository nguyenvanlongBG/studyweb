<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'confidential_id',
        'content',
        'type'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
     public function confidentials(){
        $this->belongsTo(Confidential::class);
    }

}
