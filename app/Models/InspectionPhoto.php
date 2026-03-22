<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $service_order_id
 * @property int $uploaded_by
 * @property string $photo_type
 * @property string $photo_path
 * @property string|null $label
 * @property string|null $notes
 * @property string $inspection_stage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $type_display
 * @property-read \App\Models\ServiceOrder $serviceOrder
 * @property-read \App\Models\User $technician
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereInspectionStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto wherePhotoType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereServiceOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InspectionPhoto whereUploadedBy($value)
 * @mixin \Eloquent
 */
class InspectionPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'uploaded_by',
        'photo_type',
        'photo_path',
        'label',
        'notes',
        'inspection_stage',
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get a display-friendly label for the photo type.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->photo_type) {
            'exterior'    => '🖥️ Device Exterior',
            'ram'         => '🧠 RAM Module',
            'storage'     => '💾 Storage Drive',
            'processor'   => '⚙️ Processor / CPU',
            'motherboard' => '🔌 Motherboard',
            default       => '📸 Other',
        };
    }
}
