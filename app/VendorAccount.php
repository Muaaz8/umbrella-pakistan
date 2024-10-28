<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorAccount extends Model
{
    protected $fillable=[
        'number',
        'name',
        'address'
    ];
}
