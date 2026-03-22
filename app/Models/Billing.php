<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $customer_name
 * @property string|null $description
 * @property numeric $amount
 * @property string $status
 * @property string|null $invoice_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Billing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'description',
        'amount',
        'status',
        'invoice_date',
    ];
}
