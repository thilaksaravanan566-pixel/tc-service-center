<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $created_by
 * @property string $category
 * @property string $description
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon $expense_date
 * @property string|null $receipt_path
 * @property string $payment_mode
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $creator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expense extends Model
{
    protected $fillable = [
        'branch_id', 'created_by', 'category', 'description',
        'amount', 'expense_date', 'receipt_path', 'payment_mode', 'notes',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function categoriesForSelect(): array
    {
        return [
            'rent'        => 'Rent / Lease',
            'utilities'   => 'Utilities (Electricity, Water)',
            'salaries'    => 'Salaries & Wages',
            'parts'       => 'Spare Parts Purchase',
            'marketing'   => 'Marketing & Advertising',
            'equipment'   => 'Equipment & Tools',
            'transport'   => 'Transport & Delivery',
            'misc'        => 'Miscellaneous',
        ];
    }
}
