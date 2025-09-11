<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedPhoneModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'model_name',
        'model_code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get approved models by brand
     */
    public static function getByBrand($brand)
    {
        return self::where('brand_name', $brand)
                   ->where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('model_name')
                   ->get();
    }

    /**
     * Get all approved brands
     */
    public static function getApprovedBrands()
    {
        return self::where('is_active', true)
                   ->distinct()
                   ->pluck('brand_name')
                   ->sort()
                   ->values();
    }

    /**
     * Check if a model is approved
     */
    public static function isModelApproved($brand, $modelCode)
    {
        return self::where('brand_name', $brand)
                   ->where('model_code', $modelCode)
                   ->where('is_active', true)
                   ->exists();
    }

    /**
     * Get model by code
     */
    public static function getByCode($modelCode)
    {
        return self::where('model_code', $modelCode)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get all models grouped by brand
     */
    public static function getModelsGroupedByBrand()
    {
        return self::where('is_active', true)
                   ->orderBy('brand_name')
                   ->orderBy('sort_order')
                   ->orderBy('model_name')
                   ->get()
                   ->groupBy('brand_name');
    }
}