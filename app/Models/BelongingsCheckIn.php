<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelongingsCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'name',
        'type',
        'quantity',
        'description',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
