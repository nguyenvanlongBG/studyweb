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
        // Role xem, chỉnh sửa
        'status'
        // Đang làm hoặc hoàn thành
    ];
    public function users(){
        $this->hasOne(User::class);
    }
      public function tests(){
        $this->hasMany(Test::class);
    }
    
}
