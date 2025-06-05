<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'featured_image',
        'meta_title',
        'meta_tags',
    ];

    public function getFeaturedImageAttribute($value)
    {
        return $value ? \App\Helper::check_bucket_files_url($value) : null;
    }

    public function getMetaTagsAttribute($value)
    {
        return $value ? json_decode($value) : null;
    }

    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->content), 300);
    }

}
