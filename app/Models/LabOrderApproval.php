<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabOrderApproval extends Model
{
    public $fillable = [
        'tbl_order_id','order_id','doctor_id','product_id','user_id','status','msg','test_cd'
    ];

}
