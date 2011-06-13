<?

class page_team_budgets extends Page {

    //public $controller='Controller_Budget';

    function initMainPage() {
        $model = $this->add('Model_Payment_Me');
        $model->addCondition('user_id',$this->api->getUserID());

        $c = $this->add('CRUD_TeamBudgets');
        //$model->getField('days_spent')->caption('Cur Mandays');
        $c->setModel($model, array('budget','total_tm_hours','total_hours'));
        if ($c->grid) {
        }
    }

}

