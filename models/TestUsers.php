<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class TestUsers extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    protected $primaryKey = 'testuser_id';


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_tests_users';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'test' => [Test::class,'key' => 'testuser_test_id','otherKey' => 'test_id'],
        'user' => [User::class,'key' => 'testuser_user_id','otherKey' => 'user_id'],
    ];
}
