<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'role', 'department',
        'last_login', 'status', 'position',
        'company_name', 'employee_id',
        'profile_picture', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
