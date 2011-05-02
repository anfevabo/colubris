<?php
class Controller_Importer extends AbstractController {
    public $controller_name='Controller_Timesheet';
    public $model_name='Model_Timesheet';
    public $task;

    function init(){
        parent::init();
		if(!$this->task)$this->task=$this->add($this->model_name);
    }
}
