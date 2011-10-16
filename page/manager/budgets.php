<?

class page_manager_budgets extends Page {

    //public $controller='Controller_Budget';

    function page_index() {

        $t=$this->add('Tabs');

        $t
            ->addTab('Active')
            ->add('CRUD_ManagerBudgets')
            ->setModel('Budget_Active',null,
                    array('id','name','project','client','deadline','state','priority','amount','amount_spent','team'));

        $t
            ->addTab('Unconfirmed')
            ->add('CRUD_ManagerBudgets')
            ->setModel('Budget_Unconfirmed',null,
                    array('id','name','project','client','deadline','amount_eur','amount_spent','team'));

        $t
            ->addTab('Old')
            ->add('CRUD_ManagerBudgets')
            ->setModel('Budget_Old',null,
                    array('id','name','project','client','deadline','amount_eur','amount_spent','team'));

    }

    function page_team() {
      //  $g = $this->add('MVCGrid');
        $this->api->stickyGET('budget_id');
        $crud=$this->add('CRUD','grid2');
        $model=  $this->add('Model_Payment');
        $model->setMasterField('budget_id',$_GET['budget_id']);
        $crud->setModel($model,array('id','user','user_id','hourly_rate','total_reports','total_hours','total_spent'));
        if($crud->grid)$crud->grid->addTotals();

        $this->add('H3')->set('Unaccountable:');
        $m=$this->add('Model_Timesheet')
            ->addCondition('budget_id',$_GET['budget_id']);
        $g=$this->add('MVCGrid');
        $g->setModel($m,array('user'));
        $g->addColumn('text','user_id','User ID');
        $g->addColumn('text','pid','PID');
        $g->addColumn('number','cnt','Reports');
        $g->addColumn('real','hours');
        $g->dq->group('user_id');
        $g->dq->field('count(*) cnt');
        $g->dq->field('round(sum(minutes)/60,1) hours');
        $g->dq->field('(select id from payment pa where pa.budget_id=T.budget_id and pa.user_id=T.user_id) pid');
        $g->dq->having('pid is null');
        $g->addTotals();
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
