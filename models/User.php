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
        'user_name'         => 'required|between:4,116',
        //'user_email'        => 'required|email',
        'user_level'        => 'required|numeric|min:1|max:10',
        //'user_regnumber'    => 'required|unique:samubra_exam_users'
    ];

    protected $fillable = ['user_name','user_password','user_email','user_regdate','user_ip','user_firstname','user_lastname','user_birthdate','user_birthplace','user_regnumber','user_ssn','user_level','user_verifycode','user_otpkey'];
    //protected $guarded = ['user_password_confirmation'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_password'];

    public $belongsToMany  = [
        'groups' => [
            UserGroup::class,
            'table'    => 'samubra_exam_usrgroups',
            'key'      => 'usrgrp_user_id',
            'otherKey' => 'usrgrp_group_id',
            'parentKey' => 'user_id',
            'relatedKey' => 'group_id'
        ],
    ];

    public function beforeValidate()
    {
        if(request()->has('user_password'))
            $this->rules['user_password'] = 'required|alpha_num|min:4|confirmed';
    }

    public function beforeCreate()
    {
        $post = post();
        $this->user_password = password_hash($this->user_password, PASSWORD_DEFAULT);

        unset($this->user_password_confirmation);

        if(!$this->user_level)
            $this->user_level = 1;
        $this->user_regdate = now()->toDateTimeString();
        $this->user_ip = request()->getClientIp();
    }
    
}
