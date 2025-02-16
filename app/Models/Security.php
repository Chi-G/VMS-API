<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Security extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'security';

    protected $guard = 'security';

    protected $fillable = [
        'name', 'email', 'password',
        'position', 'company_name',
        'employee_id', 'profile_picture'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
