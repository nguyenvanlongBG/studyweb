<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelGroup extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'number_member',
        'fee'
    ];
    public function groups(){
        $this->hasMany(Group::class);
    }
}
