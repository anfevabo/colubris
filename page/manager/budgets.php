<?

class page_manager_budgets extends Page {

    //public $controller='Controller_Budget';

    function initMainPage() {
        $c = $this->add('CRUD_ManagerBudgets');
        $c->setModel('Budget_Active',array('id','name','project','client','deadline','amount_eur','amount_spent','team'));

        if ($c->grid) {
            $c->grid->addColumnPlain('template,expander', 'team', 'Team')->setTemplate('<?$team?> people');
            $c->grid->addColumnPlain('expander', 'scope', 'Budget Scope');
            $c->grid->getController()->scopeFilter($c->grid->dq);
        }
    }

    function page_team() {
      //  $g = $this->add('MVCGrid');
        $this->api->stickyGET('budget_id');
        $crud=$this->add('CRUD');
        $model=  $this->add('Model_Payment');
        $model->setMasterField('budget_id',$_GET['budget_id']);
        $crud->setModel($model,array('id','user','user_id','hourly_rate'));

        $this->add('H3')->set('Unaccountable:');
        $m=$this->add('Model_Timesheet')
            ->addCondition('budget_id',$_GET['budget_id']);
        $g=$this->add('MVCGrid');
        $g->setModel($m);
        $g->dq->group('user_id');
    }

    function page_scope() {
        $this->add('View_Hint')->set('Scope defines what works will be performed inside this budget');

        $c = $this->add('Controller_Screen');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('MVCGrid')->setController($c);

        $c = $this->add('Controller_Task');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('MVCGrid')->setController($c);

        $c = $this->add('Controller_Report');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('MVCGrid')->setController($c);
    }

}
