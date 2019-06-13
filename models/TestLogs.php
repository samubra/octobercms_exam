<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class TestLogs extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_tests_logs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'answers' => [
            Answer::class,
            'table'    => 'samubra_exam_tests_logs_answers',
            'key'      => 'logansw_testlog_id',
            'otherKey' => 'logansw_answer_id',
            'pivot' => ['logansw_selected', 'logansw_order','logansw_position']
        ]
    ];

    public $belongsTo = [
        'test_user' => [
            TestUsers::class,
            'key' => 'testlog_testuser_id',
            'otherKey' => 'testuser_id'
        ],
    ];
}
