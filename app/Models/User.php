<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $mobile
 * @property string|null $vehicle_number
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property numeric $salary
 * @property string|null $biometric_id
 * @property int|null $branch_id
 * @property numeric|null $current_lat
 * @property numeric|null $current_lng
 * @property string|null $location_updated_at
 * @property int $is_online
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalaryPayment> $salaryPayments
 * @property-read int|null $salary_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBiometricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLocationUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVehicleNumber($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'salary',
        'biometric_id',
        'branch_id',
        'phone',
        'address',
        'mobile',
        'vehicle_number',
        'is_online',
        'current_lat',
        'current_lng',
        'location_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: A user (Technician) can have many Service Orders.
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'technician_id');
    }

    /**
     * Salary payments for this employee.
     */
    public function salaryPayments(): HasMany
    {
        return $this->hasMany(SalaryPayment::class);
    }

    /**
     * Branch this employee belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Helper: Check if the user is an Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: Check if the user is a Technician.
     */
    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    /**
     * Relationship: An admin user might just have an admin role, but a dealer user will have a Dealer profile.
     */
    public function dealer()
    {
        return $this->hasOne(Dealer::class);
    }

    /**
     * Helper: Check if the user is a Dealer.
     */
    public function isDealer(): bool
    {
        return $this->role === 'dealer';
    }
}