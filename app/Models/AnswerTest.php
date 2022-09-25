<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_test_id',
        'content',
        'user_id',
    ];
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
