<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'slug',
        'template',
        'name',
        'brief',
        'page_content',
        'banner_image',
        'mobile_banner_image',
        'image',
        'video_link',
        'link',
        'title',
        'heading',
        'sub_heading',
        'sort_order',
        'status',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'is_delete'
    ];

    function getChildPage()
    {
        return $this->hasMany(CmsPage::class, 'parent_id', 'id');
    }
}