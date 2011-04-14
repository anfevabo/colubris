<?php

class page_manager_reports extends Page{

	function initMainPage(){
		$crud=$this->add('CRUD');
		$m=$crud->setModel('Timesheet');
		if($crud->grid){
	//		$crud->grid->addColumn('expander','tasks');
            $crud->grid->addPaginator(50);
            $crud->grid->addTotals();
            //$crud->grid->dq->order('date,id');

            $crud->grid->last_column='title';$crud->grid->makeSortable();
            $crud->grid->last_column='user';$crud->grid->makeSortable('user');
            $crud->grid->last_column='minutes';$crud->grid->makeSortable();

            $crud->grid->addQuickSearch(array('title'),'ReportQuickSearch');
            //$crud->grid->dq->debug();
		}
	}
    /*
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
    */
}
class ReportQuickSearch extends QuickSearch {
    function init(){
        parent::init();

        $this->addField('DatePicker','from')->setAttr('style','width: 100px');
        $this->addField('DatePicker','to')->setAttr('style','width: 100px');
    }
    function applyDQ($q){
        if($this->get('from'))$q->where('date>=',$this->get('from'));
        if($this->get('to'))$q->where('date<=',$this->get('to'));
        return parent::applyDQ($q);
    }
}
