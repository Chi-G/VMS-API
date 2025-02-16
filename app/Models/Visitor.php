<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_number', 'email', 
        'address', 'is_vip', 'is_blacklisted',
        'emergency_contact', 'special_requirements', 'photo'
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function belongingsCheckIns()
    {
        return $this->hasMany(BelongingsCheckIn::class);
    }
}
