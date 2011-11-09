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
        $this->addField('quotations')->calculated(true)->type('int');

        $this->addField('demo_url');
        $this->addField('prod_url');


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
    function calculate_quotations(){
        return $this->add('Model_Quote')
            ->dsql()
            ->field('count(*)')
            ->where('quote.project_id=pr.id')
            ->select();
    }
}
