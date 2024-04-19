<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'currency',
        'billing_cycle',
        'size',
        'payment_terms',
        'room_capacity',
        'fee_amount',
        'created_by',
        'updated_by',
    ];

    // Relationship with OfficeRent
    public function officeRents()
    {
        return $this->hasMany(OfficeRent::class);
    }

    // Relationship with User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with User (Updater)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    protected static function booted()
    {
        static::creating(function ($item) {
            $item->slug = static::uniqueSlug($item->name);
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
