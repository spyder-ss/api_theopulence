<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'field_configuration',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'field_configuration' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }
}
