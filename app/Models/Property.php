<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'location',
        'guest_capacity',
        'bedrooms',
        'bathrooms',
        'property_brief',
        'property_description',
        'property_experience',
        'spaces',
        'cancellation_policy',
        'other_important_information',
        'faqs',
        'image', // Keep this for the featured image if it's still needed
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
}
