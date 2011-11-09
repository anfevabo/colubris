<?php
class page_project extends Page {
    public $b;
    function init(){
        parent::init();

        //$this->add('View_WhatToDo');

        $p=$this->add('Model_Project')->loadData($_GET['project_id']);
        $this->api->stickyGET('project_id');

        $this->template->set($p->get());

        $morework=$this->add('Form',null,'MoreWork');
        $morework->addField('text','more','');
        $morework->setFormClass('vertical');
        $morework->addSubmit('Request a Quotation');

        // fill in budgets

        $this->b=$this->add('Model_Budget_Active');
        $budgets=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($budgets as $budget){
            $b=$this->add('Budget',null,'Budget',array('page/project','Budget'));
            $b->set($budget);
        }

        if(!$budgets)$this->template->trySet('Budget','No Jobs');


        // Completed Jobs
        $this->b=$this->add('Model_Budget_Completed');
        $budgets=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($budgets as $budget){
            $b=$this->add('Budget',null,'Completed',array('page/project','Completed'));
            $b->set($budget);
        }

        if(!$budgets)$this->template->trySet('Completed','No Jobs');
    }
    function defaultTemplate(){
        return array("page/project");
    }
}
class Budget extends View {
    function set($f){

        $a=$this->owner->b->getField('state')->listData();
        $f['status']=$a[$f['state']]?:$f['state'];

        $this->template->set($f);
    }
}
