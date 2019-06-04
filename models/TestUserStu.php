<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class TestUserStu extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $primaryKey = 'tus_id';


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_testuser_stat';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

}
