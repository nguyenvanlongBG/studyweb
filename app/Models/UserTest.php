<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Util\Test;

class UserTest extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'test_id',
        'role',
        'status'
    ];
    public function users(){
        $this->hasOne(User::class);
    }
      public function tests(){
        $this->hasMany(Test::class);
    }
    
}
