<?
class Model_Project extends Model_Table {
	public $entity_code='project';
	public $table_alias='pr';

	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('descr')->dataType('text');

		$this->addField('client_id')->refModel('Model_Client');

        $this->addField('budgets')->calculated(true)->type('int');


        $u=$this->api->getUser();
        if($u->get('is_client')){
            $this->addCondition('client_id',$u->get('client_id'));
        }else{
            if($sc=$this->api->recall('scope')){
                if($sc['client'])$this->addCondition('client_id',$sc['client']);
            }
        }
	}
    function calculate_budgets(){
        return $this->add('Model_Budget')
            ->dsql()
            ->field('count(*)')
            ->where('bu.project_id=pr.id')
            ->select();
    }
}
