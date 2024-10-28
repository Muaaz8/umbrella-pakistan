<?php

namespace App\Repositories;

use App\Models\TblFaq;
use App\Repositories\BaseRepository;
use DB;

/**
 * Class TblFaqRepository
 * @package App\Repositories
 * @version January 27, 2021, 10:16 pm UTC
 */

class TblFaqRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'question',
        'answer',
        'labtest_ids',
        'status'
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
        return TblFaq::class;
    }

    public function getMultipleTestNamesViaIds($ids)
    {
        $id = explode(",", $ids);
        $names = [];
        $names2 = [];
        foreach ($id as $item) {
            $data = DB::table('tbl_products')
                ->select('name')
                ->where('id', '=', $item)
                ->where('mode', '=', 'lab-test')
                ->where('product_status', '=', 1)
                ->where('is_approved', '=', 1)
                ->get();
            array_push($names, $data[0]);
        }
        foreach (json_decode(json_encode($names), true) as $key => $n) {
           array_push($names2, $n['name']);
        }
        return json_encode($names2);
    }
    public function allGeneralCategory()
    {
        return TblFaq::where('type','general')->get();
    }
}
