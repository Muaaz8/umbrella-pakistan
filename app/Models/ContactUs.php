<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent;

class ContactUs extends Model
{
    protected $table = 'contacts_us';
    protected $fillable=[
        'id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        
    ];


}
