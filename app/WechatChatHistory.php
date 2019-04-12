<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WechatChatHistory extends Model
{
    protected $table = 'wechat_chat_history';
    public $timestamps = false;
    protected $guarded = [];
}
