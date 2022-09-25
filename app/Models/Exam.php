<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'point',
        'test_id',
        'user_id',
    ];
    public function user()
    {
        $this->belongsTo(User::class);
    }
    public function test()
    {
        $this->belongsTo(Test::class);
    }
    public function choosed()
    {
        $this->hasMany(Choosed::class);
    }
}
