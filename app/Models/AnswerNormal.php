<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerNormal extends Model
{
    use HasFactory;
     protected $fillable = [
          'user_id',
          'question_normal_id',
          'content'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
   

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       
    ];
    public function users()
    {
        $this->belongsTo(User::class);
    }
    public function questionNormals()
    {
        $this->belongsTo(QuestionNormal::class);
    }
   
   
}
