<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories_id',
        'users_id',
    ];
}
