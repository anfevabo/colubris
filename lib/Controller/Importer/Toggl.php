<?php
class Controller_Importer_Toggl extends Controller_Importer_Csv {
	public $task;
	/*User,Client,Project,Description,Billable,Start time,End time,Duration,Tags
	  max,Agiletech,LF,Watch List Page,Нет,03/24/2011 20:48,03/24/2011 21:46,00:58:15,""
	  */
	function init(){
		parent::init();
		$this->task=$this->add('Controller_Timesheet');
		$this->task->setMasterField('user_id',$this->api->auth->get('id'));
	}
	/*
	function importFromText($text){
		$this->task->unloadData();
		/*
		$this->task
			->set('minutes','5')
			->set('date',date('Y-m-d'))
			->set('title',$text)
			->update();
	}
			*/
}
