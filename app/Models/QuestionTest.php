<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'point',
        'test_id',
        'result_id',
    ];
    public function test()
    {
        $this->belongsTo(Test::class);
    }
    public function chooseQuestion()
    {
        $this->hasMany(ChooseQuestion::class);
    }
    public function choosed()
    {
        $this->hasMany(Choosed::class);
    }
}
