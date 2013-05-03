<?php

class page_manager_reports extends Page{

	function initMainPage(){
        $f=$this->add('ReportQuickSearch',null,null,array('form/quicksearch'));
        $grid=$this->add('Grid');
        $m=$grid->setModel('Timesheet',array('title','user','budget','date'));
        $grid->addColumn('money','amount')->makeSortable();
        $grid->addPaginator(50);
        $grid->addTotals();
        $grid->dq->order('date desc,id desc');
        //$f->useDQ($grid->dq);

        $grid->last_column='title';$grid->makeSortable();
        $grid->last_column='user';$grid->makeSortable('user');
        $grid->last_column='minutes';$grid->makeSortable();

            //$crud->grid->addQuickSearch(array('title'),'ReportQuickSearch');
            //$crud->grid->dq->debug();
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
class ReportQuickSearch extends Filter {
    function init(){
        parent::init();

        //$this->setFormClass('horizontal');

        $this->addField('dropdown','group')->setValueList(array('none','user_id'=>'user','date(date)'=>'date','budget_id'=>'budget'));
        $this->addField('autocomplete/basic','user_id')->setModel('Developer');
        $this->addField('autocomplete/basic','budget_id')->setModel('Budget');
        $this->addField('DatePicker','from')->setAttr('style','width: 100px');
        $this->addField('DatePicker','to')->setAttr('style','width: 100px');
        $this->addSubmit();
    }
    function applyDQ($q){
        if($this->get('from'))$q->where('date>=',$this->get('from'));
        if($this->get('to'))$q->where('date<=',$this->get('to'));
        if($this->get('user_id'))$q->where('user_id',$this->get('user_id'));
        if($this->get('budget_id'))$q->where('budget_id',$this->get('budget_id'));
        if($this->get('group')){
            $q->group($this->get('group'));
            $q->field('sum(minutes) amount');
        }else{
            $q->field('minutes amount');
        }
        

        //parent::applyDQ($q);
    }
}
