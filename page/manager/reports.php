<?php

class page_manager_reports extends Page{

	function initMainPage(){
		$crud=$this->add('CRUD');
		$m=$crud->setModel('Timesheet');
		if($crud->grid){
			$crud->grid->addColumn('expander','tasks');
            $crud->grid->addPaginator(50);
            $crud->grid->dq->order('date,id');

            $crud->grid->addQuickSearch(array('title'));
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
