<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glory extends Model
{
    use HasFactory;
    protected $fillable=[
        'target_id',
        // test or competition
        'type',
        // type test
        'status'
    ];

}
