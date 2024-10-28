<?php

namespace App\Repositories;

use App\Models\MentalConditions;
use App\Repositories\BaseRepository;

/**
 * Class MentalConditionsRepository
 * @package App\Repositories
 * @version January 6, 2021, 10:20 pm UTC
*/

class MentalConditionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'content'
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
        return MentalConditions::class;
    }
}
