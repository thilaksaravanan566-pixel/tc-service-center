<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $month
 * @property int $year
 * @property numeric $base_salary
 * @property numeric $bonus
 * @property numeric $deductions
 * @property float $net_salary
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property int|null $paid_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $employee
 * @property-read string $month_name
 * @property-read \App\Models\User|null $paidByUser
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereBaseSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereNetSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryPayment whereYear($value)
 * @mixin \Eloquent
 */
class SalaryPayment extends Model
{
    protected $fillable = [
        'user_id', 'month', 'year', 'base_salary',
        'bonus', 'deductions', 'status', 'paid_at', 'paid_by', 'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'bonus'       => 'decimal:2',
        'deductions'  => 'decimal:2',
        'paid_at'     => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function getNetSalaryAttribute(): float
    {
        return (float)($this->base_salary + $this->bonus - $this->deductions);
    }

    public function getMonthNameAttribute(): string
    {
        return \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }
}
