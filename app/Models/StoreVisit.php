<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'assigned_to',
        'visit_date',
        'purpose',
        'status',
        'notes',
        'check_in_at',
        'check_in_lat',
        'check_in_lng',
        'check_out_at',
        'visit_photos',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'check_in_at' => 'datetime',
        'check_in_lat' => 'decimal:8',
        'check_in_lng' => 'decimal:8',
        'check_out_at' => 'datetime',
        'visit_photos' => 'json',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
