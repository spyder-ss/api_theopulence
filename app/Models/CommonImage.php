<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonImage extends Model
{
    protected $fillable = [
        'common_image_category_id',
        'image',
        'alt_text',
        'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(CommonImageCategory::class, 'common_image_category_id');
    }
}
