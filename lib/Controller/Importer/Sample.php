<?php
class Controller_Importer_Sample extends Controller_Importer {
	public $task;
	function init(){
		parent::init();
		$this->task=$this->add('Controller_Timesheet');
		$this->task->setMasterField('user_id',$this->api->auth->get('id'));
	}
	function importFromText($text){
		$this->task->unloadData();
		$this->task
			->set('minutes','5')
			->set('date',date('Y-m-d'))
			->set('title',$text)
			->update();
	}
}
