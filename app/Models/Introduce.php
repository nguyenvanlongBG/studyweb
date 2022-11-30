<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introduce extends Model
{
    use HasFactory;
    protected $fillable=[
        'content'
    ];
    public function adminstrator(){
        $this->hasMany(Administrator::class);
    }
}
