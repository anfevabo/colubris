<?
class Model_Task extends Model_Table {
	public $entity_code='task';
	public $table_alias='tsk';

	function defineFields(){
		parent::defineFields();

		$this->addField('name');

		$this->addField('descr_original')->dataType('text');

		$this->addField('estimate')->dataType('money');
		$this->addField('cur_progress')->dataType('int');

		$this->addField('deviation')->dataType('text');

		$this->addField('budget_id')->refModel('Model_Budget');
		$this->addField('screen_id')->refModel('Model_Screen');



	}
}
