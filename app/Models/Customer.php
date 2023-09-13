<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email_address',
        'phone_number',
        'address',
        'details',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        // Creating event listener
        static::creating(function ($customer) {
            $customer->created_by = Auth::id();
            $customer->updated_by = Auth::id();
            $customer->deleted_by = Auth::id();
        });

        // Updating event listener
        static::updating(function ($customer) {
            $customer->updated_by = Auth::id();
        });
    }
}
