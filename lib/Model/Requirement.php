<?
class Model_Requirement extends Model_Table {
	public $entity_code='requirement';
	public $table_alias='req';

	function defineFields(){
		parent::defineFields();

		$this->addField('name');
		$this->addField('type');
		$this->addField('ref');
		$this->addField('descr')->datatype('text');

		$this->addField('budget_id')
			->refModel('Model_Budget');

		$this->addField('project_id')
			->refModel('Model_Project');

		$this->addField('sub_estimate')
			->calculated(true);

		$this->addField('estimate')
			->datatype('money');

		$this->addField('tot_estimate')
			->caption('Full Estimate')
			->calculated(true);
	}
	function scopeFilter($dsql){
		if($sc=$this->api->recall('scope')){
			if($sc['project'])$dsql->where('project_id',$sc['project']);
			if($sc['budget'])$dsql->where('budget_id',$sc['budget']);
		}
	}
	function calculate_sub_estimate(){
		return $this->add('Model_Task')
			->dsql()
			->field('sum(tsk.estimate)')
			->where('tsk.screen_id=req.id')
			->select();

	}
	function calculate_tot_estimate(){
		return $this->add('Model_Task')
			->dsql()
			->field('coalesce(sum(tsk.estimate),0)+coalesce(req.estimate,0)')
			->where('tsk.screen_id=req.id')
			->select();

	}

}
