<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class FrontDeskUser extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name', 'email', 'password',
        'position', 'company_name',
        'employee_id', 'profile_picture'
    ];

    protected $hidden = [
        'password', 'remember_token', 
    ];
}
