<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'image',
        'video',
        'testimonial',
        'sort_order',
        'featured',
        'status',
        'is_delete',
        'property_id',
        'rating',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
