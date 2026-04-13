<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Allow these fields to be saved to the database
    protected $fillable = [
        'title', 'description', 'category', 'image', 
        'author', 'author_avatar', 'link', 'tags'
    ];

    // Automatically convert the tags JSON from the DB into a PHP array (and vice versa)
    protected $casts = [
        'tags' => 'array',
    ];
}
