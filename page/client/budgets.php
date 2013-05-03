<?php

class page_client_budgets extends Page {

    function initMainPage() {
        $u = $this->api->auth;
        $projects = $this->add('Model_Project')->getRows(array('name'));
        foreach($projects as $project){

            $this->add('H3')->set($project['name']);
            $budget = $this->add('Model_Budget');
            $budget->addCondition('project_id',$project['id']);

            $g = $this->add('Grid_ClientBudget');
            $m = $g->setModel($budget, array('name','success_criteria', 'accepted', 'start_date','deadline',
                        'amount_eur','amount_spent'));
            //$g->addTotals();
        }

        //$g->addPaginator(10);
    }

    function page_team() {
        $this->api->stickyGET('id');
        $crud = $this->add('CRUD_ReadOnly');
        $model = $this->add('Model_Payment');
        $model->addCondition('budget_id', $_GET['id']);
        $crud->setModel($model, array('id', 'user', 'user_id', 'hourly_rate'));
    }

}

