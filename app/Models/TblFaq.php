<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblFaq
 * @package App\Models
 * @version January 27, 2021, 10:16 pm UTC
 *
 * @property string $question
 * @property string $answer
 * @property integer $status
 */
class TblFaq extends Model
{
    use SoftDeletes;

    public $table = 'tbl_faq';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'question',
        'answer',
        'labtest_ids',
        'status',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'question' => 'string',
        'answer' => 'string',
        'labtest_ids' => 'string',
        'status' => 'integer',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'question' => 'nullable|string',
        'answer' => 'nullable|string',
        'labtest_ids' => 'nullable|string',
        'status' => 'required|integer',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'type' => 'nullable'
    ];

    
}
