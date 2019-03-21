<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class Question extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $primaryKey = 'question_id';

    /**
     * @var string The database table used by the model.
     */
    protected $fillable = ['question_subject_id','question_description','question_explanation','question_type','question_difficulty','question_enabled','question_position','question_timer','question_fullscreen','question_inline_answers','question_auto_next'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_exam_questions';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'question_description' => 'required'
    ];
    public $belongsTo = [
        'subject' => [Subject::class,'key' => 'question_subject_id' , 'otherKey' => 'subject_id','scope' => 'isEnabled']
    ];

    public $hasMany = [
        'answers' => [Answer::class,'key' => 'answer_question_id' ,'otherKey' => 'answer_id','scope' => 'isEnabled']
    ];
    public function scopeIsEnabled($query)
    {
        return $query->where('question_enabled', true)->orderBy('question_id', 'desc');
    }

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'question_type')
            return Exam::$examTypeMap;
        return [
            Exam::NO => '否',
            Exam::YES => '是'
        ];
    }

    public function beforeCreate()
    {
        //unset($this->user_password_confirmation);
    }
}
