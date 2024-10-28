<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table='activity_log';
    protected $fillable=['activity','identity','type','user_id','user_type','party_involved','product_id','prod_cat_id','prod_sub_cat_id'];

    public static function add_activity($activity,$act_id,$flag)
    {
        $act=ActivityLog::create([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>$activity,
            'type'=>$flag,
            ]);
            if($flag=='editor_created'){
                $act->party_involved=$act_id;
                $act->save();
            }else if($flag=='product_created'){
                $act->product_id=$act_id;
                $act->save();
            }else if($flag=='product_category_created'){
                $act->prod_cat_id=$act_id;
                $act->save();
            }else if($flag=='product_sub_category_created'){
                $act->prod_sub_cat_id=$act_id;
                $act->save();
            }

    }
}
