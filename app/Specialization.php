<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable=['name','status'];
    public function speciality($doc_id)
    {
        $doc=User::find($doc_id);
        $spec=$this->where('id',$doc['specialization'])->first();
        return $spec['name'];
    }
}
