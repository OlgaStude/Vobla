<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class friendsRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reciever_id',
        'sender_id',
    ];
}
