<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory;

    // Explicitly tell Laravel which table to look at
    protected $table = 'admin_users'; 

    protected $fillable = ['username', 'password'];
}
