<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $warranty_certificate_id
 * @property int $customer_id
 * @property string $description
 * @property array<array-key, mixed>|null $evidence_photos
 * @property string $status
 * @property string|null $admin_notes
 * @property int|null $handled_by
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\WarrantyCertificate $certificate
 * @property-read \App\Models\Customer $customer
 * @property-read string $status_color
 * @property-read \App\Models\User|null $handler
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereEvidencePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereHandledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyClaim whereWarrantyCertificateId($value)
 * @mixin \Eloquent
 */
class WarrantyClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'warranty_certificate_id',
        'customer_id',
        'description',
        'evidence_photos',
        'status',
        'admin_notes',
        'handled_by',
        'resolved_at',
    ];

    protected $casts = [
        'evidence_photos' => 'array',
        'resolved_at'     => 'datetime',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(WarrantyCertificate::class, 'warranty_certificate_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Status badge colour mapping for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'reviewing' => 'blue',
            'approved'  => 'green',
            'rejected'  => 'red',
            'resolved'  => 'gray',
            default     => 'gray',
        };
    }
}
