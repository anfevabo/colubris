<?php

class page_client_budgets extends Page {

    function initMainPage() {
        $budget = $this->add('Model_Budget');
        $budget->getField('mandays')->caption('Quoted');
        $budget->getField('days_spent')->caption('Total Spent');

        $g = $this->add('Grid_ClientBudget');
        $m = $g->setModel($budget, array('name', 'deadline', 'accepted', 'amount_eur', 'mandays', 'days_spent', 'days_spent_lastweek', 'project'));
        $m->addCondition('accepted', true);
        $m->addCondition('closed', false);
        //$g->addColumn('text', 'quoted');
        $g->dq->order('coalesce(deadline,"2999-01-01") asc,id desc');
        //$g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);
    }

}

