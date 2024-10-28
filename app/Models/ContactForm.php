<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    protected $table = 'contact_form';
    protected $fillable=[
        'id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        
    ];}
