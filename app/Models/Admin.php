<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Admin extends Model
{
     use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];
}
