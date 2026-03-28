<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobUpdate extends Model
{
    protected $fillable = [
        'service_order_id',
        'updated_by',
        'status',
        'previous_status',
        'note',
        'photo_path',
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** Human-readable status badge colour */
    public function statusColor(): string
    {
        return match($this->status) {
            'received'         => 'blue',
            'diagnosing'       => 'yellow',
            'repairing'        => 'orange',
            'ready'            => 'green',
            'packing'          => 'teal',
            'completed'        => 'emerald',
            'cancelled'        => 'red',
            default            => 'slate',
        };
    }
}
