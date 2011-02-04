<?
class Model_Report extends Model_Table {
	public $entity_code='report';
	public $table_alias='R';

	function defineFields(){
		parent::defineFields();

		$this->newField('task_id')->refModel('Model_Task');

		$this->newField('budget_id')->refModel('Model_Budget');
		$this->newField('project_id')->refModel('Model_Project');

		$this->newField('user_id')->refModel('Model_User');

		$this->newField('date')->datatype('date')->mandatory(true)->defaultValue(date('Y-m-d'));
		$this->newField('result')->datatype('text');
		$this->newField('amount')->datatype('int')->mandatory(true);

		$this->setMasterField('user_id',$this->api->auth->get('id'));
	}
}
