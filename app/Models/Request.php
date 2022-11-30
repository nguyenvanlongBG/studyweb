<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'target_id',
        'status',
        'type'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
}
