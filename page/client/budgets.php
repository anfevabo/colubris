<?php

class page_client_budgets extends Page {

    function initMainPage() {
        $u = $this->api->getUser();
        $project = $this->add('Model_Project');
        $q = $project->dsql();
        if (!$u->get('is_admin')) {
            $q->where('client_id', $u->get('client_id'));
        }
        $q->field('id');
        $result = $q->do_getAll();
        $budget = $this->add('Model_Budget');
        $budget->getField('mandays')->caption('Quoted');
        $budget->getField('days_spent')->caption('Total Spent');

        $g = $this->add('Grid_ClientBudget');
        $m = $g->setModel($budget, array('name', 'deadline', 'accepted','closed', 'amount_eur', 'mandays', 'days_spent', 'days_spent_lastweek', 'project'));
      $g->addColumn('html', 'depleted', 'Depleted %');
        $g->last_column = 'deadline';
        $g->makeSortable();
        $g->last_column = 'accepted';
        $g->makeSortable();
        $g->last_column = 'amount_eur';
        $g->makeSortable();
        $g->last_column = 'mandays';
        $g->makeSortable();
        $g->last_column = 'closed';
        $g->makeSortable();
        $ids = array();
        foreach ($result as $row) {
            if (is_array($row)) {
                foreach ($row as $column) {
                    $ids[] = $column;
                }
            }
        }

        if (count($ids) == 0) {
            $m->addCondition('project_id', -9999);
        } else {
            $m->addCondition('project_id in', implode(',', $ids));
        }
        $g->dq->order('coalesce(deadline,"2999-01-01") asc,id desc');
        $g->addPaginator(10);
    }

}

