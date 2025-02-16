<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'staff_id', 'visitor_type', 'visitor_id',
        'purpose', 'check_in', 'check_out', 'host_name',
        'building', 'floor', 'group_size', 'status'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
