<?php namespace Samubra\Exam\Models;

use Model;

/**
 * Model
 */
class User extends Model
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
    public $table = 'samubra_exam_users';

    protected $primaryKey = 'user_id';
    /**
     * @var array Validation rules
     */
    public $rules = [
        'user_name'                  => 'required|between:4,16',
        'user_email'                 => 'required|email',
        'user_password'              => 'required|alpha_num|min:4|confirmed',
        'user_password_confirmation' => 'required|alpha_num|min:4'
    ];

    protected $fillable = ['user_name','user_password','user_email','user_regdate','user_ip','user_firstname','user_lastname','user_birthdate','user_birthplace','user_regnumber','user_ssn','user_level','user_verifycode','user_otpkey'];

    public function beforeCreate()
    {
        $post = post();
        $this->password = password_hash($post['User']['user_password'], PASSWORD_DEFAULT);
        $this->user_regdate = now()->toDateTimeString();
        $this->user_ip = request()->getClientIp();
    }
}
