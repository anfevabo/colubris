<?

class page_team_budgets extends Page {

    //public $controller='Controller_Budget';

    function initMainPage() {
        $model = $this->add('Model_Budget');
        $model->addCondition('closed', 'N');

        $c = $this->add('CRUD_TeamBudgets');
        $model->getField('days_spent')->caption('Cur Mandays');
        $c->setModel($model, array('name', "deadline", 'accepted', 'mandays', 'days_spent', 'project_id'));
        if ($c->grid) {

           $g=$c->grid;
           //Depleted % = Cur Mandays / Mandays (Precentage). Red if its >100%
           //refer to http://agiletoolkit.org/doc/grid/columns
            $g->addColumn('html', 'depleted', 'Depleted %');
        }
    }

}

