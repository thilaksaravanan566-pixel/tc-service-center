<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $service_order_id
 * @property array<array-key, mixed>|null $image_paths
 * @property string|null $problem_description
 * @property string|null $ai_analysis
 * @property array<array-key, mixed>|null $suggested_issues
 * @property array<array-key, mixed>|null $suggested_parts
 * @property array<array-key, mixed>|null $troubleshooting_steps
 * @property numeric|null $confidence_score
 * @property string $diagnosis_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\ServiceOrder|null $serviceOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereAiAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereConfidenceScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereDiagnosisType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereImagePaths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereProblemDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereServiceOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereSuggestedIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereSuggestedParts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereTroubleshootingSteps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AiDiagnosis whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AiDiagnosis extends Model
{
    protected $table = 'ai_diagnoses';

    protected $fillable = [
        'customer_id', 'service_order_id', 'image_paths',
        'problem_description', 'ai_analysis', 'suggested_issues',
        'suggested_parts', 'troubleshooting_steps', 'confidence_score', 'diagnosis_type',
    ];

    protected $casts = [
        'image_paths'          => 'array',
        'suggested_issues'     => 'array',
        'suggested_parts'      => 'array',
        'troubleshooting_steps'=> 'array',
        'confidence_score'     => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}
