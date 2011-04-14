<?php
class page_manager extends Page {
    function initMainPage(){

        $g=$this->add('ReportGrid',null,'weekly_timesheets');
        $g->setModel('Timesheet',array('user','title','date','minutes'));
        $g->dq->order('date desc,id desc');
        $g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);

        $g=$this->add('ReportGrid',null,'weekly_reports');
        $g->setModel('Report',array('user','budget','date','amount'));
        $g->dq->order('date desc,id desc');
        $g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);

        $g->addFormatter('amount','flink');
        if($_GET['amount']){
            $this->js()->univ()->frameURL('Report Details',
                    $this->api->getDestinationURL('./amount',array('id'=>$_GET['amount'])))->execute();
        }

        $g=$this->add('ReportGrid',null,'open_budgets');
        $m=$g->setModel('Budget',array('name','deadline','time_pct','money_pct','feature_pct','amount_eur'));
        $m->addCondition('accepted',true);
        $m->addCondition('closed',false);
        $g->dq->order('coalesce(deadline,"2999-01-01") asc,id desc');
        //$g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);

        $g=$this->add('MVCGrid',null,'developer_stats',array('grid_striped'));
        $m=$g->setModel('Developer',array('name','timesheets_tw','reports_tw','last_timesheet'));
        //$g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);
    }
    function page_amount(){

        $this->api->stickyGET('id');

        $t=$this->add('Tabs');

        $g=$t->addTab('Breakdown')
            ->add('ReportGrid');
        $g->setModel('Timesheet')
            ->addCondition('report_id',$_GET['id']);
        $g->addTotals();

        $t->addTab('Amend')
            ->add('FormAndSave')->setModel('Report',array('user_id','client_id','budget_id','date','amount'))->loadData($_GET['id']);

        $t->addTab('Delete')
            ->add('FormDelete')->setModel('Report',array(''))->loadData($_GET['id']);
            ;

    }
    function defaultTemplate(){
        if($this->api->page=='manager')return array('page/manager');
        return parent::defaultTemplate();
    }
}
