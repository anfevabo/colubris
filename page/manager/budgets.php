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
      //  $g = $this->add('Grid');
        $this->api->stickyGET('budget_id');
        $crud=$this->add('CRUD_Payment','grid2');
        $model=  $this->add('Model_Payment');
        $model->setMasterField('budget_id',$_GET['budget_id']);
        $crud->setModel($model,array('id',
                    'user','role','user_id',

                    'total_reports','total_hours','total_spent','total_client_pays','estimated_hours',

                    // Stats for our expense
                    'hourly_rate', 'expense',

                    // Client stats
                    'client_pays', 'service_name', 'sell_rate', 'sell_total',
                    
                    
                    
                    ));
        if($crud->grid)$crud->grid->addTotals();
/*
        $this->add('H3')->set('Unaccountable:');
        $m=$this->add('Model_Timesheet')
            ->addCondition('budget_id',$_GET['budget_id']);
        $g=$this->add('Grid');
        $g->setModel($m,array('user'));
        $g->addColumn('text','user_id','User ID');
        $g->addColumn('text','pid','PID');
        $g->addColumn('number','cnt','Reports');
        $g->addColumn('real','hours');
        $g->dq->group('user_id');
        $g->dq->field('count(*) cnt');
        $g->dq->field('round(sum(minutes)/60,1) hours');
        $g->dq->field('(select id from payment pa where pa.budget_id=T.budget_id and pa.user_id=T.user_id) as pid');
        //$g->dq->having('pid is null');
        $g->addTotals();*/
    }

    function page_scope() {
        $this->add('H3')->set('Quotations');
        $this->add('CRUD')->setModel('Quote');
        /*
        $m=$this->add('Model_Wireframe');

        $c = $this->add('Controller_Screen');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('Grid')->setController($c);

        $c = $this->add('Controller_Task');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('Grid')->setController($c);

        /*
        $c = $this->add('Controller_Report');
        $c->addCondition('budget_id', $_GET['id']);
        $this->add('Grid')->setController($c);
        */
    }

}
class CRUD_Payment extends CRUD {
    public $grid_class='Grid_Payment';
    function initComponents(){
        parent::initComponents();

        if($this->form){
            $this->form->setFormClass('basic atk-form-basic-2col');
            $h=$this->form->add('Hint')
                ->set('Estimated fields are generated and auto-filled from the "Specs" section. Use rate for pay-per-reports only.');

            $h1=$this->form->add('H3')->set('Expenses');
            $s=$this->form->addSeparator();

            $this->form->add('Order')
                ->move($h1,'before','user_id')
                ->move($s,'after','expense')
                ->move($h,'after',$s)
                ->now();

            $this->form->getElement('estimated_hours')->setFieldHint('This number is imported from "Specs" section. It is used here to compare actual hours reported with estimate');
            $this->form->getElement('hourly_rate')->setFieldHint('For fixed-price agreement, use 0 here');
            $this->form->getElement('expense')->setFieldHint('Fixed price agreement amount');
        }
        
    }
}
