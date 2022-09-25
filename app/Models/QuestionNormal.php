<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionNormal extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
    ];
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
