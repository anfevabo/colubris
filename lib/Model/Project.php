<?
class Model_Project extends Model_Table {
	public $entity_code='project';
	public $table_alias='pr';

	function defineFields(){
		parent::defineFields();

		$this->newField('name');
		$this->newField('descr')->dataType('text');

		$this->newField('client_id')->refModel('Model_Client');
	}
}
