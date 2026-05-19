<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoContent extends Model
{
    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'page_slug',
    ];
}
