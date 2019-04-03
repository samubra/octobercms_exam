<?php
/**
 * Created by PhpStorm.
 * User: samub
 * Date: 2019/3/20
 * Time: 10:18
 */

namespace Samubra\Exam\Models;


class UserImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'user_name'         => 'required|between:4,116',
        'user_email'        => 'required',
        'user_level'        => 'required|numeric|min:1|max:10',
        'user_regnumber'    => 'required|unique:samubra_exam_users'
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {

                trace_sql();
                $user = new User;
                $user->fill($data);

                $user->user_password = str_replace(PHP_EOL, '', substr($user->user_regnumber, -8));
                //$user->user_password = password_hash('123456', PASSWORD_DEFAULT);
                $password = password_hash(str_replace(PHP_EOL, '', substr($user->user_regnumber, -8)), PASSWORD_DEFAULT);
               // trace_log('明文密码：' . substr($user->user_regnumber, -8));
               // trace_log('加密密码：' . password_hash(str_replace(PHP_EOL, '', substr($user->user_regnumber, -8)), PASSWORD_DEFAULT));
               // trace_log('加密密码2：' . $user->user_password);
                if (!$user->user_level)
                    $user->user_level = 1;
                $user->user_regdate = now()->toDateTimeString();
                $user->user_ip = request()->getClientIp();
                //trace_log($user->user_firstname);
                //if ($user->user_full_name) {
                //    $user->user_lastname = substr($user->user_firstname, 2);
                //    $user->user_firstname = substr($user->user_firstname, 1, 1);

                trace_log($user->user_firstname);
                    //dd($user);
                    //trace_log($user->user_firstname);
              //  }
                $user->groups = [$this->group_id];
                $user->user_level = $this->user_level;
                $user->save();



                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }

    public function getGroupIdOptions()
    {
        //return ['1'=>'22'];
        return UserGroup::lists('group_name','group_id');
    }
}