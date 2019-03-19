<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class UserGroup extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    protected $primaryKey = 'group_id';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_user_groups';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
