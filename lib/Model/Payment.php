<?

class Model_Payment extends Model_Table {

    public $entity_code = 'payment';
    public $table_alias = 'p';

    function defineFields() {
        parent::defineFields();

        $this->newField('user_id')->refModel('Model_User');
        $this->newField('budget_id')->refModel('Model_Budget');
        $this->newField('hourly_rate');

//        $u=$this->api->getUser();
//        if($u->isInstanceLoaded() && $u->get('is_client')){
//            $this->addCondition('client_id',$u->get('client_id'));
//        }
    }

    function calculate_cur_mandays() {
        return $this->add('Model_Report')
                ->dsql()
                ->field('round(sum(R.amount/60/8),1)')
                ->where('R.budget_id=bu.id')
                ->select();
    }

    public function beforeModify(&$data) {
        if(isset($_GET['id'])){
        $data['budget_id'] = $_GET['id'];
        }
        parent::beforeModify($data);
    }

}
