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
        //'user_email'        => 'required',
        'user_level'        => 'required|numeric|min:1|max:10',
        'user_regnumber'    => 'required|unique:samubra_exam_users'
    ];

    protected $testUser;
    protected $test;
    protected $testLog;
    protected $testLogAnswer;
    protected $user;
    protected $postData;
    protected $question = null;

    protected $updatedMessage = '';

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {
            $this->postData = $data;
            try {
                //trace_sql();
                $users = User::where('user_name',$this->postData['user_name'])->with('groups')->get();
                $currentGroupId = isset($this->postData['group_id']) ? [$this->postData['group_id']]:[$this->group_id];
                //trace_log($users->count());
                if($users->count()){
                    $this->user = $users->first();
                    $groupIds = $this->user->groups->pluck('group_id')->toArray();
                    //trace_log($groupIds);
                    if(!in_array($currentGroupId, $groupIds)){
                    	$this->user->groups()->attach($currentGroupId);
                    	$this->user->save();
                    	$this->updatedMessage .= '当前用户已存在,但是添加了用户组';
                    }else{
                    	$this->updatedMessage .= '当前用户已存在';
                    }
                    
                }else {
                    $this->user = new User;
                    $this->user->fill($this->postData);
                    $this->user->user_password = str_replace(PHP_EOL, '', substr($this->user->user_regnumber, -8));
                    if (!$this->user->user_level)
                        $this->user->user_level = $this->user_level;
                    $this->user->user_regdate = now()->toDateTimeString();
                    $this->user->user_ip = request()->getClientIp();

                    $this->user->user_email = $this->user->user_regnumber.'@tiikoo.cn';

                    //trace_log($this->user->user_firstname);
                    $this->user->groups =$currentGroupId;
                    $this->user->save();
                    $this->updatedMessage .= '用户创建成功';
                }

                if(isset($this->postData['test_id']) && isset($this->postData['tq_id'])){
                    $this->test = Test::where('test_id',$this->postData['test_id'])->first();
                    $this->createTestuser();
                    $this->getQuestion();
                    if(is_null($this->question))
                        continue;
                    $this->createTestLogs();
                    $this->createLogAnswers();
                }

                $this->logWarning($row,$this->updatedMessage.'。');
                $this->updatedMessage = '';
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

    protected function createTestuser()
    {
        $testUsers = TestUsers::where('testuser_test_id',$this->postData['test_id'])->with('test_logs_count')->where('testuser_user_id',$this->user->user_id)->get();
        //trace_log('testuserIDs:'.$testUsers->count());
        if($testUsers->count())
        {
            $this->testUser = $testUsers->first();
            $this->updatedMessage .= ',测试用户已存在';
        }else{
            $this->testUser = new TestUsers();
            $this->testUser->testuser_test_id = $this->postData['test_id'];
            $this->testUser->testuser_user_id = $this->user->user_id;
            $this->testUser->testuser_status = 1;
            $this->testUser->testuser_creation_time = $this->test->test_begin_time;
            $this->testUser->save();
            $this->updatedMessage .= ',测试用户添加成功';

            $testUserStu = new TestUserStu();
            $testUserStu->tus_date = $this->testUser->testuser_creation_time;
            $testUserStu->save();
        }
    }

    protected function getQuestion()
    {
        $questions = Question::where('question_tq_id',$this->postData['tq_id'])->with('answers','answers_count')->get();
        //trace_log('questionsIDs:'.$questions->count());
        if($questions->count())
            $this->question = $questions->first();
        else
            $this->question = null;
    }
    protected function createTestLogs()
    {
        if($this->question)
        {
            $this->testLog = new TestLogs();
            $this->testLog->testlog_testuser_id = $this->testUser->testuser_id;
            $this->testLog->testlog_question_id = $this->question->question_id;
            $this->testLog->testlog_score = '0.000';
            $this->testLog->testlog_display_time = null;//$this->test->test_begin_time;
            $this->testLog->testlog_reaction_time = '0';

            $testLogCount = TestLogs::where('testlog_testuser_id',$this->testUser->testuser_id)->get()->count();

            $this->testLog->testlog_order = $testLogCount + 1;
            //trace_log($this->question->answers);
            $this->testLog->testlog_num_answers = $this->question->answers->count();

            $this->testLog->save();

            $this->updatedMessage .= ',试题已添加成功';
        }else{
            $this->updatedMessage .= ',试题没有找到';
        }

    }

    protected function createLogAnswers()
    {
        if($this->question){
            $answers = $this->question->answers;

            foreach ($answers as $key => $answer){
                $this->testLog->answers()->add($answer,['logansw_order' => $key +1]);
            }
            $this->updatedMessage .= ',试题答案添加成功';
        }

    }
}