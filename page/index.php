<?php
class Page_index extends Page {
	function init(){
		parent::init();
		$c=$this->add('View_Columns');

		$c1=$c->addColumn();
		$c2=$c->addColumn();

		$c1->add('H2')->set('You should work on:');
		$w=$c1->add('View_WhatToDo');
		$w->addButton('New Project Wizard')->js('click')->univ()->redirect('/admin/newproj');
		
		$w->addColumnPlain('button','start');
		if($_GET['start']){
			$this->api->memorize('task',$_GET['start']);
			$c2->js()->reload()->execute();
		}

		$task=$this->api->getCurrentTask();
			//recall('task',null);
		//$t=$this->add
		if($task){

			$c2->add('H2')->set('Report Time on '.$task->get('name'));
			$c_log=$this->add('Controller_Report');
			$c_log->setMasterField('task_id',$task->get('id'));
			$c_log->setActualFields(array('amount','date','result'));
			$f=$c2->add('MVCForm')->setController($c_log);
			if($f->isSubmitted()){
				$f->update();
				$f->js()->univ()->page($this->api->getDestinationURL())->execute();
			}



			$c2->add('View_TaskDescription')->setController($task);
		}else{
			$c2->add('View_Hint')->set('Welcome to Colubris. Platform for team and client collaboration');

		}

	}
}
