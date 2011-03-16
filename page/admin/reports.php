<?php

class page_admin_reports extends Page{

	function initMainPage(){
		$crud=$this->add('CRUD');
		$m=$crud->setModel('Report');
		if($crud->grid){
			$crud->grid->addColumn('expander','tasks');
		}
	}
	function page_tasks(){
		if(!$_GET['tasks'])$_GET['tasks']=$_GET['id'];
		$this->api->stickyGET('tasks');

		$crud=$this->add('CRUD','c2');
		$m=$this->add('Model_Timesheet');
		$crud->setModel($m);
		$m->addCondition('report_id',$_GET['tasks']);
		if($crud->grid)$crud->grid->addTotals();
		return;
	}
}
