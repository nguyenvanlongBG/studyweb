<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'image_url'
    ];
    public function users(){
        $this->hasMany(User::class, 'mission_users');
    }
}
