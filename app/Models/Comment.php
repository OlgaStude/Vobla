<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = [
        'posts_id',
        'users_id',
        'text',
        'likes',
        'dislikes'
    ];

    public function get_user()
    {
        return $this->hasMany(User::class);
    }
}
