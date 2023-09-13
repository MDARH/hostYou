<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Hosting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'plan_id',
        'domain_name',
        'next_renewal_date',
        'payment_amount',
        'payment_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];
    protected static function boot()
    {
        parent::boot();

        // Creating event listener
        static::creating(function ($hosting) {
            $hosting->created_by = Auth::id();
            $hosting->updated_by = Auth::id();
            $hosting->deleted_by = Auth::id();
        });

        // Updating event listener
        static::updating(function ($hosting) {
            $hosting->updated_by = Auth::id();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function hostingPlan()
    {
        return $this->belongsTo(HostingPlan::class, 'plan_id');
    }
}
