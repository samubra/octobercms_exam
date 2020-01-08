<?php namespace Samubra\Exam\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Exam\Models\Question;
use Samubra\Exam\Models\Test;
use Samubra\Exam\Models\TestLogs;
use Samubra\Exam\Models\TestUsers;

class Users extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController',
        ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    protected $test=null;
    protected $testUser = null;
    protected $testLog = null;
    protected $questionsCount = 0;

    protected $percent = 0.9;



    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Exam', 'exam', 'user');
        //var_dump(password_hash(substr('500238199509287497',-6), PASSWORD_DEFAULT));
    }


    public function completeTest($test_id)
    {
        $testModel =Test::where('test_id',$test_id)->first();
        $testUserList = TestUsers::where('testuser_test_id',$test_id)->with('user')->get();
        foreach ($testUserList as $testUser) {
            dump($testUser->test_logs);
        }

    }
    public function stintTest($test_id = null)
    {
       $test_id = 10;
        trace_sql();
        if(is_null($test_id)){
            $testList = Test::all();
            foreach ($testList as $test){
                trace_log('开始处理：'.$test->test_name);
                $this->test = $test;
                $this->dealWithQuestions();
            }
        }else{
            $this->test = Test::where('test_id',$test_id)->first();
            //trace_log($this->test);
            trace_log('开始处理：'.$this->test->test_name);
            $this->dealWithQuestions();
        }

    }

    public function dealWithQuestions()
    {
        $testUsersList = TestUsers::where('testuser_test_id',$this->test->test_id)->pluck('testuser_id');

        $testLogs = TestLogs::whereIn('testlog_testuser_id',$testUsersList->toArray())->get();

        foreach ($testUsersList as $testUser){
            trace_log('开始处理用户：'.$testUser.'的判断题；');
            $pdts = $testLogs->filter(function ($testLogs) use($testUser){
                return $testLogs->testlog_testuser_id === $testUser && $testLogs->testlog_num_answers === 2;
            });
            $count = $pdts->count();
            trace_log('共'.$count.'道判断题！');
            $percent = $this->percent * 70;
            if($count === 70)
            {
                trace_log('开始设置比例：'. ($percent));

                $randLogs = $pdts->random(70 - $percent);

                $randIdLists = $randLogs->pluck('testlog_order','testlog_question_id');
                foreach ($randLogs as $log){
                    $log->answers()->detach();
                    $log->delete();
                }
                $questionsList = Question::whereNotIn('question_id',array_keys($randIdLists))->get(70 - $percent);
                trace_log($questionsList->toArray());
            }

        }
    }

}
