<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'image',
        'brief',
        'description',
        'author',
        'posted_on',
        'link',
        'video_link',
        'sort_order',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'status',
        'is_delete'
    ];
}