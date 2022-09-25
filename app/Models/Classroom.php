<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'level',
        'status',
    ];
    public function users()
    {
        $this->belongsToMany(User::class);
    }
    public function tests()
    {
        $this->belongsToMany(Test::class, 'classroom_tests');
    }
}
