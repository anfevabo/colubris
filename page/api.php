<?php
class page_api extends Page {
    function init(){
        parent::init();

    }
    function page_quote_create(){
        $m=$this->add('Model_Quote');
        $p=$this->add('Model_Project')->loadData($_REQUEST['project_id']);
        if(!$p->isInstanceLoaded())die('ouch');

        $m->set($_REQUEST)->update();

        $this->response(array('success'=>$m->get('id')));
    }
    function page_project_list(){
        $this->response($this->add('Model_Project')->getRows());
        exit;
    }
    function page_budget_list(){
        $this->response($this->add('Model_Budget_Accepted')->getRows());
        exit;
    }
    function page_project_update(){
        $b=$this->add('Model_Budget')->loadData($_REQUEST['budget_id']);
        if(!$b->isInstanceLoaded())die('no such budget');

        $b->set('total_pct',$_REQUEST['total_pct']);
        $b->set('timeline_html',$_REQUEST['timeline_html']);

        $b->update();
        $this->response(array('success'=>'OK'));
    }
    function response($result){
        echo json_encode($result);
        exit;
    }
}
