<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'key', 'value'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class); 
    }
}
