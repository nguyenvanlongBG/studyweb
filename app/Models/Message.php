<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
        'sender_id',
        'group_id',
        'content',
        'type',
        // type video picture text
        'pin'
        //  ghim tin nhắn 0 không ghim càng lớn càng ghim lên đầu
    ];
    public function senders(){
        $this->belongsTo(User::class, 'sender_id');
    }
    public function groups(){
           $this->belongsTo(Group::class);
    }
}
