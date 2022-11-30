<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    use HasFactory;
    protected $fillable=[
        'usrl_avatar',
        'introduce_id',
        'name',
        'content'
    ];
    public function introduces(){
             $this->belongsTo(Introduce::class);
    }
}
