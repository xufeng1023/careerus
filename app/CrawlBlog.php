<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawlBlog extends Model
{
    protected $table = 'crawlblogs';

    public function getRouteKeyName()
    {
        return 'title';
    }
}
