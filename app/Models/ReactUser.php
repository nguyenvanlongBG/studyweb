<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactUser extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'confidential_id',
        'react_id'
    ];
    public function users(){
        $this->belongsTo(User::class);
    }
       public function confidentials(){
        $this->belongsTo(Confidential::class);
    }
       public function reacts(){
        $this->hasOne(React::class);
    }
}
