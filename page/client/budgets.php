<?php
class page_client_budgets extends Page {
    function initMainPage(){
        if($_GET['details']){
            $this->api->setScope('budget',$_GET['details']);
            $this->js()->univ()->location($this->api->getDestinationURL('./details'))->execute();
        }else{
            $this->api->setScope('budget');
        }

        $v=$this->add('View',null,null,array('view/client/budgets'));
        $m=$this->add('Model_Budget');
        $data=$m->getRows();
        foreach($data as $row){
            $r=$v->add('View',null,'rows','row');

            $m->loadData($row['id']);
            $row['project']=$m->getRef('project_id')->get('name');
            $r->template->set($row);

            $tabs=$r->add('Tabs');
            $tab=$tabs->addTab('Reports');
            $g=$tab->add('MVCGrid');
            $g->setModel('Report');//->addCondition('proj');
            $g->addTotals();

            $tab=$tabs->addTab('Timeshetes');
            $g=$tab->add('MVCGrid');
            $g->setModel('Timesheet');//->addCondition('proj');
            $g->addTotals();

            $tab=$tabs->addTab('Requirements');
            $g=$tab->add('MVCGrid');
            $g->setModel('Requirement');//->addCondition('proj');
            $g->addTotals();

            $tab=$tabs->addTab('Bugs');

            $r->add('MVCGrid',null,'Hours',array('grid_striped'))
                ->setModel('User',array('name','id'));


        }
        //$l=$this->add('MVCLister',null,null,array('view/client/budgets'));
        //$l->setModel('Budget');

        $this->js(true)->_selector('.progress')->progressbar(array('value'=>37));
    }
    function page_details(){
        $this->add('Button')->setLabel('Back')->js('click')->univ()->location($this->api->getDestinationURL('..'));
    }
}

