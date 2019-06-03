<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class Test extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $primaryKey = 'test_id';


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_tests';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'groups' => [
            UserGroup::class,
            'table' => 'samubra_exam_testgroups',
            'key' => 'tstgrp_test_id',
            'otherKey' => 'tstgrp_group_id'
        ],
    ];
}
