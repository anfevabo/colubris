<?php

class page_manager extends Page {

    function initMainPage() {
        $budget = $this->add('Model_Budget');
        $budget->getField('mandays')->caption('Quoted');
        $g = $this->add('ReportGrid', null, 'open_budgets');
        $m = $g->setModel($budget, array('name', 'days_spent', 'mandays', 'deadline'));
        $m->addCondition('accepted', true);
        $m->addCondition('closed', false);
        //$g->addColumn('text', 'quoted');
        $g->addColumn('html', 'difference', 'Difference %');
        $g->dq->order('coalesce(deadline,"2999-01-01") asc,id desc');
        //$g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);


        $g = $this->add('MVCGrid', null, 'developer_stats');
        $m = $g->setModel('Developer', array('name', 'timesheets_tw', 'reports_tw', 'last_timesheet'));
        //$g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);
    }

    function page_amount() {

        $this->api->stickyGET('id');

        $t = $this->add('Tabs');

        $g = $t->addTab('Breakdown')
                        ->add('ReportGrid');
        $g->setModel('Timesheet')
                ->addCondition('report_id', $_GET['id']);
        $g->addTotals();

        $t->addTab('Amend')
                ->add('FormAndSave')->setModel('Report', array('user_id', 'client_id', 'budget_id', 'date', 'amount'))->loadData($_GET['id']);

        $t->addTab('Delete')
                ->add('FormDelete')->setModel('Report', array(''))->loadData($_GET['id']);
        ;
    }

    function defaultTemplate() {
        if ($this->api->page == 'manager'
            )return array('page/manager');
        return parent::defaultTemplate();
    }

}
