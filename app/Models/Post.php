<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'image_preview',
        'content',
        'user_id',
        'status',
        // status public or private in system
        'approve'
    ];
public function users(){
    $this->belongsTo(User::class);
}
}
