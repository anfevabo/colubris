<?php
class page_team extends Page {
    function initMainPage(){
        $ac_url=$this->api->locateURL('js','autocomplete/ui.autocomplete.css');

		//$this->api->template->append('js_include',
         //       '<link rel="stylesheet" type="text/css" href="'.$ac_url.'" />');


        $g=$this->add('ReportGrid',null,'just_reported');
        $m=$g->setModel('Timesheet',array('budget','title','date','minutes'));
        $m->addCondition('user_id',$this->api->getUserID());
        $g->dq->order('date desc,id desc');
        $g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);

        /*
        $this->add('Button',null,'just_reported_buttons')->set('Edit')
            ->js('click')->univ()->frameURL('Manually add');
        $this->add('Button',null,'just_reported_buttons')->set('Import');
        $this->add('Button',null,'just_reported_buttons')->set('Integrate');
        */
        $f=$this->add('Form',null,'just_reported_buttons',array('form_empty'));
        $f->add('Icon')->set('basic-question')
            ->js('click')->univ()->message('<h4>Hint</h4><p>Select several items from your reports. Pick appropriate budget, and '.
                    'task, then click on <i class="atk-icon atk-icons-orange atk-icon-office-pencil"></i> to update '.
                    'entries.</p><p>Do this every day!!</p>');
        $l=$f->addField('line','budget_id','');
        $l->setModel('Budget');
        $f->addField('autocomplete','budget_id','Budget:&nbsp;')->emptyValue('..budget')->setAttr('style','width: 100px')->setModel('Budget');
        //$f->addField('autocomplete','task_id','')->emptyValue('..task')->setAttr('style','width: 100px')->setModel('Task');
        $f->add('Icon')->set('office-pencil');

        $g->addSelectable($l);

        return;

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
        if($this->api->page=='team')return array('page/team');
        return parent::defaultTemplate();
    }
}
