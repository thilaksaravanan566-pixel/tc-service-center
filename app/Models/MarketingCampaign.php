<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $description
 * @property string|null $content
 * @property string|null $image_path
 * @property string|null $discount_code
 * @property numeric|null $discount_percent
 * @property string $target_audience
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $is_active
 * @property int $sent_count
 * @property int $click_count
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read bool $is_running
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereClickCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereDiscountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereSentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereTargetAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketingCampaign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MarketingCampaign extends Model
{
    protected $fillable = [
        'name', 'type', 'description', 'content', 'image_path',
        'discount_code', 'discount_percent', 'target_audience',
        'start_date', 'end_date', 'is_active', 'sent_count', 'click_count', 'created_by',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'discount_percent' => 'decimal:2',
        'start_date'       => 'date',
        'end_date'         => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsRunningAttribute(): bool
    {
        if (!$this->is_active) return false;
        $now = now()->toDateString();
        if ($this->start_date && $this->start_date->toDateString() > $now) return false;
        if ($this->end_date && $this->end_date->toDateString() < $now) return false;
        return true;
    }
}
