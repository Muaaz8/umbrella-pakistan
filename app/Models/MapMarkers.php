<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MapMarkers
 * @package App\Models
 * @version December 14, 2020, 4:08 pm UTC
 *
 * @property string $name
 * @property string $state
 * @property string $address
 * @property string $city
 * @property string $zip_code
 * @property string $marker_type
 * @property string $marker_icon
 * @property string $lat
 * @property string $long
 */
class MapMarkers extends Model
{
    use SoftDeletes;

    public $table = 'tbl_map_markers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'state',
        'address',
        'city',
        'zip_code',
        'marker_type',
        'marker_icon',
        'lat',
        'long'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'state' => 'string',
        'address' => 'string',
        'city' => 'string',
        'zip_code' => 'string',
        'marker_type' => 'string',
        'marker_icon' => 'string',
        'lat' => 'string',
        'long' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:191',
        'state' => 'nullable|string',
        'address' => 'nullable|string|max:191',
        'city' => 'nullable|string',
        'zip_code' => 'nullable|string|max:191',
        'marker_type' => 'nullable|string|max:191',
        'marker_icon' => 'nullable|string|max:191',
        'lat' => 'nullable|string|max:191',
        'long' => 'nullable|string|max:191',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
