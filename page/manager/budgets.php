<?

class page_manager_budgets extends Page {

    //public $controller='Controller_Budget';

    function initMainPage() {
        $c = $this->add('CRUD');
        $c->setModel('Budget');

        if ($c->grid) {
            $c->grid->addColumnPlain('expander', 'team', 'Team Access');
            $c->grid->addColumnPlain('expander', 'scope', 'Budget Scope');
            $c->grid->getController()->scopeFilter($c->grid->dq);
            $c->grid->last_column = 'deadline';
            $c->grid->makeSortable();
             $c->grid->last_column = 'accepted';
            $c->grid->makeSortable();
             $c->grid->last_column = 'closed';
            $c->grid->makeSortable();
             $c->grid->last_column = 'amount_eur';
            $c->grid->makeSortable();
             $c->grid->last_column = 'mandays';
            $c->grid->makeSortable();
        //     $c->grid->last_column = 'pr';
            $c->grid->getColumn('project_id')->makeSortable();
        }
    }

    function page_team() {
        $g = $this->add('MVCGrid');
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
