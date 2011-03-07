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
			))
            ;
		$this->newField('mandays')
			->datatype('int')
            ;

		$this->newField('client_id')
			->refModel('Model_Client');

		$this->newField('project_id')
			->refModel('Model_Project');
	}
	function scopeFilter($dsql){
		if($sc=$this->api->recall('scope')){
			if($sc['client'])$dsql->where('client_id',$sc['client']);
		}
	}
}
