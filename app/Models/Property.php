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
        'amenities',
        'spaces',
        'cancellation_policy',
        'other_important_information',
        'faqs',
    ];
}
