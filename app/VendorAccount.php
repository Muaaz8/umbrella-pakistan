<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorAccount extends Model
{
    protected $fillable=[
        'number',
        'name',
        'address',
        'vendor_number',
        'vendor',
        'user_id',
        'image',
        'category',
        'is_active',
    ];
}
