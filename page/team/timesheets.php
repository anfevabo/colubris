<?php

class page_team_timesheets extends Page{

	function initMainPage(){
		$crud=$this->add('CRUD');
        $m=$this->add('Model_Timesheet');
        $m->setMasterField('user_id',$this->api->getUserID());
        $m->getField('date')->defaultValue(date('Y-m-d'));
		$crud->setModel($m,array('title','budget_id','date','minutes'));
        if($grid=$crud->grid){
			$quicksearch=$grid->add('ReportQuickSearch',null,'quick_search',array('form/quicksearch'));
            $grid->addPaginator(50);
            $grid->addTotals();
            $grid->dq->order('date desc,id desc');
           // $f->useDQ($grid->dq);

            $grid->last_column='budget';$grid->makeSortable();
            $grid->last_column='title';$grid->makeSortable();
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
        }else{
			$crud->form->onSubmit(function($f) use ($crud){
				$f->memorize('budget_id',$f->get('budget_id'));
			});
			$crud->form->set('budget_id',$crud->form->recall('budget_id',null));
		}
	}
}
class ReportQuickSearch extends QuickSearch {
    function init(){
        parent::init();

        $this->setFormClass('horizontal');
        $this->addField('checkbox','no_budget','n/b');
        $this->addField('autocomplete','budget_id','B: ')->setModel('Budget');
        $this->addField('DatePicker','from','Date: ')->setAttr('style','width: 100px');
        $this->addField('DatePicker','to','-')->setAttr('style','width: 100px');
    }
    function applyDQ($q){
        if($this->get('no_budget'))$q->where('isnull(budget_id)');
        if($this->get('from'))$q->where('date>=',$this->get('from'));
        if($this->get('to'))$q->where('date<=',$this->get('to'));
        if($this->get('budget_id'))$q->where('budget_id',$this->get('budget_id'));
        parent::applyDQ($q);
    }
	/*
	function defaultTemplate(){
		return array('form_empty');
	}
	*/
}

