<?php namespace Samubra\Exam\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

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


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Exam', 'exam', 'user');
        //var_dump(password_hash(substr('500238199509287497',-6), PASSWORD_DEFAULT));
    }
}
