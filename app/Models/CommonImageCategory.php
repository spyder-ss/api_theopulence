<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonImageCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'brief',
        'image',
        'status',
    ];

    public function images()
    {
        return $this->hasMany(CommonImage::class);
    }
}
