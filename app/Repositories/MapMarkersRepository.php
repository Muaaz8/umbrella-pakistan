<?php

namespace App\Repositories;

use App\Models\MapMarkers;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class MapMarkersRepository
 * @package App\Repositories
 * @version December 14, 2020, 4:08 pm UTC
*/

class MapMarkersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MapMarkers::class;
    }

    public function getMarkersByType($type)
    {
        $TT = '';
        ($type['type'] === 'lab-test') ? $TT = 'lab' : (($type['type'] ===  'medicine') ? $TT = 'pharmacy' : $type['type']);

        $data = DB::table('tbl_map_markers')
            ->where('marker_type',  $TT)
            ->get();
        return $data;
    }

}
