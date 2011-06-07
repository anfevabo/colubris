<?
class Model_Budget extends Model_Table {
	public $entity_code='budget';
	public $table_alias='bu';

	function defineFields(){
		parent::defineFields();

		$this->newField('name');
		$this->newField('deadline')->datatype('date');
		$this->newField('accepted')->datatype('boolean');
		$this->newField('closed')->datatype('boolean');
		$this->newField('amount_eur')->datatype('money');
		$this->newField('success_criteria')
			->datatype('list')
			->listData(array(
                0=>'Unconfirmed',
				1=>'Requirements completed',
				2=>'Mandays worked',
				3=>'Budget depleted',
				4=>'Deadline Reached',
			))
            ;
		$this->newField('mandays')
			->datatype('int')
            ;
		$this->newField('cur_mandays')
			->datatype('int')
			->calculated(true)
            ;
    $this->newField('days_spent')
			->datatype('int')
			->calculated(true)
            ;

		$this->newField('project_id')
			->refModel('Model_Project')
            ;

        $u=$this->api->getUser();
        if($u->isInstanceLoaded() && $u->get('is_client')){
            $this->addCondition('client_id',$u->get('client_id'));
        }
	}
	function scopeFilter($dsql){
		if($sc=$this->api->recall('scope')){
			if($sc['client'])$dsql->where('client_id',$sc['client']);
		}
	}
	function calculate_cur_mandays(){
		return $this->add('Model_Report')
			->dsql()
			->field('round(sum(R.amount/60/7),1)')
			->where('R.budget_id=bu.id')
			->select();
	}
        function calculate_days_spent(){
		return $this->add('Model_Timesheet')
			->dsql()
			->field('sum(T.minutes)/60/8')
			->where('T.budget_id=bu.id')
			->select();
	}
}
