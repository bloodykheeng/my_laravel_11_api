<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'logo_url',
        'country_of_origin',
        'created_by',
        'updated_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted()
    {
        static::creating(function ($car) {
            $car->slug = static::uniqueSlug($car->name);
        });
    }

    public static function uniqueSlug($string)
    {
        $baseSlug = Str::slug($string, '-');
        if (static::where('slug', $baseSlug)->doesntExist()) {
            return $baseSlug;
        }

        $counter = 1;
        // Limiting the counter to prevent infinite loops
        while ($counter < 1000) {
            $slug = "{$baseSlug}-{$counter}";
            if (static::where('slug', $slug)->doesntExist()) {
                return $slug;
            }
            $counter++;
        }

        // Fallback if reached 1000 iterations (should ideally never happen)
        return "{$baseSlug}-" . uniqid();
    }
}
