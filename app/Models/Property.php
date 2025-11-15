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
        'price',
        'external_redirection_url',
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_main', 1);
    }
}
