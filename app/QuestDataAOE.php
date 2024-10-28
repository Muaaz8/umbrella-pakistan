<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestDataAOE extends Model
{
    public $timestamps = false;

    public $table = 'quest_data_aoes';

    protected $fillable = [
        'id',
        'LEGAL_ENTITY',
        'TOPLAB_PERFORMING_SITE',
        'UNIT_CD',
        'TEST_CD',
        'ANALYTE_CD',
        'AOE_QUESTION',
        'ACTIVE_IND',
        'PROFILE_COMPONENT',
        'INSERT_DATETIME',
        'AOE_QUESTION_DESC',
        'SUFFIX',
        'RESULT_FILTER',
        'TEST_CD_MNEMONIC',
        'TEST_FLAG',
        'UPDATE_DATETIME',
        'UPDATE_USER',
        'COMPONENT_NAME',
        'UPDATE_TYPE'
    ];
}
