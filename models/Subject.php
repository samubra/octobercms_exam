<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class Subject extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    protected $primaryKey = 'subject_id';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_subjects';
    protected $fillable = ['subject_id','subject_module_id','subject_name','subject_description','subject_enabled','subject_user_id'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'subject_name' => 'required'
    ];

    public $belongsTo = [
        'module' => [Module::class,'key' => 'subject_module_id','otherKey' => 'module_id','scope' => 'isEnabled'],
        'user' => [User::class,'key' => 'subject_user_id','otherKey' => 'user_id'],
    ];

    public $hasMany = [
        'questions' => [Question::class,'key' => 'question_subject_id' ,'otherKey' => 'subject_id']
    ];

    public function getSubjectEnabledOptions()
    {
        return [
            Exam::ENABLE => '启用',
            Exam::DISABLE => '禁用'

        ];
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('subject_enabled', true)->orderBy('subject_name', 'desc');
    }
}
