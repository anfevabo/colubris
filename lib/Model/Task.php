<?
class Model_Task extends Model_Table {
	public $entity_code='task';
	public $table_alias='tsk';

	function defineFields(){
		parent::defineFields();

		$this->addField('name');

		$this->addField('budget_id')->refModel('Model_Budget');
	}
}
