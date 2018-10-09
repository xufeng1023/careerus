<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawlBlog extends Model
{
    protected $table = 'crawlBlogs';

    public function getRouteKeyName()
    {
        return 'title';
    }
}
