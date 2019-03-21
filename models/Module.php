<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class Module extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $primaryKey = 'module_id';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_modules';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo  = [
        'user' => [
            User::class,
            'key'      => 'module_user_id',
            'otherKey' => 'user_id'
        ]
    ];

    public $hasMany = [
        'subjects' => [Subject::class,'key' => 'subject_module_id' ,'otherKey' => 'module_id']
    ];

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'module_enabled') {
            return [
                Exam::ENABLE => '启用',
                Exam::DISABLE => '禁用'
            ];
        }

        
        return [
            Exam::NO => '否',
            Exam::YES => '是'
        ];

    }

    public function scopeIsEnabled($query)
    {
        return $query->where('module_enabled', true)->orderBy('module_id', 'desc');
    }
}
