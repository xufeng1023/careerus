<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function getRouteKeyName()
    {
        return 'invoice_id';
    }
}
