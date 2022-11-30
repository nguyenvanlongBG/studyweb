<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable=[
        'content',
        'url'
    ];
    public function users(){
        $this->belongsToMany(User::class, 'notification_users');
    }
}
