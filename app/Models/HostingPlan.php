<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class HostingPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'plan_name',
        'capacity',
        'price',
        'plan_details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        // Creating event listener
        static::creating(function ($hosting_plan) {
            $hosting_plan->created_by = Auth::id();
            $hosting_plan->updated_by = Auth::id();
            $hosting_plan->deleted_by = Auth::id();
        });

        // Updating event listener
        static::updating(function ($hosting_plan) {
            $hosting_plan->updated_by = Auth::id();
        });
    }
}
