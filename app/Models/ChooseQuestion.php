<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChooseQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_test_id',
        'content',
    ];
    public function questionTest()
    {
        $this->belongsTo(QuestionTest::class);
    }
    public function choosed()
    {
        $this->hasMany(Choosed::class);
    }
    
}
