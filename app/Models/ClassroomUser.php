<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomUser extends Model
{
    use HasFactory;
    protected $fillable=[
         'user_id',
         'classroom_id',
         
         //status=0 chưa duyệt status=1 đã duyệt 
    ];
}
