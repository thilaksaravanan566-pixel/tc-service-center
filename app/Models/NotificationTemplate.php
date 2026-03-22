<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $event_trigger
 * @property string|null $email_subject
 * @property string|null $email_body
 * @property string|null $sms_body
 * @property string|null $whatsapp_body
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereEmailBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereEmailSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereEventTrigger($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereSmsBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereWhatsappBody($value)
 * @mixin \Eloquent
 */
class NotificationTemplate extends Model
{
    protected $fillable = [
        'name',
        'event_trigger',
        'email_subject',
        'email_body',
        'sms_body',
        'whatsapp_body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
