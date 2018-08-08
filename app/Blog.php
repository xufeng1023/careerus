<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];

    public function excerpt()
    {
        $str = html_entity_decode(strip_tags($this->content));
        return str_limit(preg_replace('/[\\a-zA-Z{},:;""\[\]]/', '', $str), 200);
    }
}
