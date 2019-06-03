<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class Answer extends Model
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
    public $table = 'samubra_exam_answers';
    protected $primaryKey = 'answer_id';

    /**
     * @var string The database table used by the model.
     */
    protected $fillable = ['answer_question_id','answer_description','answer_explanation','answer_isright','answer_enabled','answer_position','answer_keyboard_key'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'answer_description' => 'required'
    ];

    public $belongsTo = [
        'question' => [Question::class,'key' => 'answer_question_id' , 'otherKey' => 'question_id'
            //,'scope' => 'isEnabled'
            ]
    ];


    public $belongsToMany = [
        'testLogs' => [
            TestLogs::class,
            'table'    => 'samubra_exam_tests_logs_answers',
            'otherKey'      => 'logansw_testlog_id',
            'key' => 'logansw_answer_id',
            'pivot' => ['logansw_selected', 'logansw_order','logansw_position']
        ]
    ];
    public function scopeIsEnabled($query)
    {
        return $query->where('answer_enabled', true)->orderBy('answer_id', 'desc');
    }

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'answer_isright')
            return [
                '1' => '正确',
                '0' => '错误',
            ];
        if($fieldName == 'answer_keyboard_key')
        {
            $keyboard = [];
            for ($ascii = 32; $ascii <= 126; ++$ascii) {
                if ($ascii == 32) {
                    $keyboard[$ascii] = 'SP';
                } else {
                    $keyboard[$ascii] = htmlspecialchars(chr($ascii), ENT_NOQUOTES);
                }
            }
            return $keyboard;
        }
        return [
            Exam::NO => '否',
            Exam::YES => '是'
        ];
    }
}
