<?
class Model_Task extends Model_Table {
	public $entity_code='task';
	public $table_alias='tsk';

	function defineFields(){
		parent::defineFields();

		$this->addField('name');
		$this->addField('priority')->datatype('int');

		$this->addField('descr_original')->dataType('text');

		$this->addField('estimate')->dataType('money');
		$this->addField('cur_progress')->dataType('int')->calculated(true);

		$this->addField('deviation')->dataType('text');

		$this->addField('budget_id')->refModel('Model_Budget');
		$this->addField('screen_id')->refModel('Model_Screen');


        /*
        $this->addRelatedEntity('bu2','budget','budget_id','left');
        $this->addField('client_id')
            ->readonly(true)
            ->relEntity('bu2')
            ->refModel('Model_Client')
            ;
            */

	}
	function enScope($dsql){
		if($sc=$this->api->recall('scope')){
			if($sc['budget'])$dsql->where('budget_id',$sc['budget']);
		}
        $u=$this->api->getUser();
        if($u->get('is_client')){
            $this->addCondition('client_id',$u->get('id'));
        }
	}
	function calculate_cur_progress(){
		return $this->api->db->dsql()
			->table('report')
			->where('task_id=tsk.id')
			->field('sum(amount)')
			->select();
	}
}
