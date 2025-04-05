<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class image_crud extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'image_route',
        'content',
        'author',
        'labels'
    ];

}

