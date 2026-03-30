<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
