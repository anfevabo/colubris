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


        // Quotes
        $this->b=$this->add('Model_Quote');
        $quotes=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($quotes as $quote){
            $b=$this->add('Quote',null,'Quote',array('page/project','Quote'));
            $b->set($quote);
        }

        if(!$quotes)$this->template->trySet('Quote','No Quotes');



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
class Link extends HtmlElement {
    function init(){
        parent::init();
        $this->href('#');
        $this->setElement('a');
    }
    function href($url){
        $this->setAttr('href',$url);
        return $this;
    }
}
class Quote extends View {
    function set($f){
        $this->template->set($f);

        $this->add('Link',null,'Details')->set('Detailed Breakdown');
            ->js('click')->univ()->dialogURL('Details',$this->api->getDestinationURL(null,array($this->name=>'details')));

        if($_GET[$this->name]=='details'){
            $m=$this->add('Model_Quote')->loadData($f['id']);
            echo $m->get('html');
            exit;
        }
        //$this->template->set('details',$this->api->getDestinationURL());
    }

}
