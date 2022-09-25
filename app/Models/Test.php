<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'time_start',
        'time_end',
    ];
    public function classes()
    {
        $this->belongsToMany(Classroom::class);
    }
}
