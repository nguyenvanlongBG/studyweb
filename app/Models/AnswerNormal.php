<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerNormal extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_normal',
        'content',
        'user_id',
    ];
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
