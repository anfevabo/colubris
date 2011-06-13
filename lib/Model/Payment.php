<?

class Model_Payment extends Model_Table {

    public $entity_code = 'payment';
    public $table_alias = 'pa';

    function defineFields() {
        parent::defineFields();

        $this->newField('user_id')->sortable(true)->refModel('Model_Developer');
        $this->newField('budget_id')->sortable(true)->refModel('Model_Budget');
        $this->newField('hourly_rate')->sortable(true);

        $this->addField('total_reports')->calculated(true)->type('int')->sortable(true);
        $this->addField('total_hours')->calculated(true)->type('real')->sortable(true);
        $this->addField('total_spent')->calculated(true)->type('money')->sortable(true);

//        $u=$this->api->getUser();
//        if($u->isInstanceLoaded() && $u->get('is_client')){
//            $this->addCondition('client_id',$u->get('client_id'));
//        }
    }

    function getTimesheets(){
        return $this->add('Model_Timesheet')
            ->dsql()
            ->where('T.budget_id=pa.budget_id and T.user_id=pa.user_id');
    }
    function calculate_total_reports(){
        return $this->getTimesheets()->field('count(*)')->select();
    }
    function calculate_total_hours(){
        return $this->getTimesheets()
            ->field('round(sum(T.minutes/60),1)')
            ->select();
    }
    function calculate_total_spent(){
        return $this->getTimesheets()
            ->field('round(sum(T.minutes/60)*hourly_rate,2)')
            ->select();
    }
}
