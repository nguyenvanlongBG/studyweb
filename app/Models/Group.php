<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable=[
'name',
'level',
'status',
'admin'
    ];
public function levels(){
   return $this->hasOne(LevelGroup::class);
}

public function admins(){
   return $this->hasOne(User::class);
}
public function messages(){
   return $this->hasMany(Message::class);
}
public function users(){
     return $this->belongsToMany(User::class, 'group_users', 'group_id','user_id');
}
public function tests(){
     return $this->belongsToMany(Test::class, 'group_tests');
}
}
