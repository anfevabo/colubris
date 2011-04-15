<?php

class page_manager_timesheets extends Page{

	function initMainPage(){
        $quicksearch=$this->add('ReportQuickSearch',null,null,array('form/quicksearch'));
		$crud=$this->add('CRUD');
		$m=$crud->setModel('Timesheet',array('title','user','budget','date','minutes'));
        if($grid=$crud->grid){
            $grid->addPaginator(50);
            $grid->addTotals();
            $grid->dq->order('date desc,id desc');
           // $f->useDQ($grid->dq);

            $grid->last_column='title';$grid->makeSortable();
            $grid->last_column='user';$grid->makeSortable('user');
            $grid->last_column='minutes';$grid->makeSortable();

            //$crud->grid->addQuickSearch(array('title'),'ReportQuickSearch');
            $quicksearch->useGrid($grid)->useFields(array('title'));

            $this->add('H3')->set('Change Selected');
            $f=$this->add('MVCForm')->setFormClass('horizontal');
            $f_sel=$f->addField('line','sel');
            $grid->addSelectable($f_sel);

            $ts=$f->setModel('Timesheet',array('client_id','project_id','budget_id','requirement_id','task_id'));

            if($f->isSubmitted()){
                $q=$ts->dsql();
                if($f->get('budget_id'))$q->set('budget_id',$f->get('budget_id'));
                //if($f->get('budget_id')$q->set('budget_id',$f->get('budget_id'));

                $ids=json_decode(stripslashes($f->get('sel')));

                $q->where('id in',$ids);

                $q->do_update();
                $grid->js()->reload()->execute();

            }
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

        //$this->setFormClass('horizontal');

        $this->addField('autocomplete','user_id')->setModel('Developer');
        $this->addField('autocomplete','budget_id')->setModel('Budget');
        $this->addField('DatePicker','from')->setAttr('style','width: 100px');
        $this->addField('DatePicker','to')->setAttr('style','width: 100px');
    }
    function applyDQ($q){
        if($this->get('from'))$q->where('date>=',$this->get('from'));
        if($this->get('to'))$q->where('date<=',$this->get('to'));
        if($this->get('user_id'))$q->where('user_id',$this->get('user_id'));
        if($this->get('budget_id'))$q->where('budget_id',$this->get('budget_id'));
        parent::applyDQ($q);
    }
}
