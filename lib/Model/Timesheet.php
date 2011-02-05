<?
class Model_Timesheet extends Model_Table {
	public $entity_code='timesheet';
	public $table_alias='T';
	function defineFields(){
		parent::defineFields();

		$this->newField('title');
		$this->newField('date')->datatype('date');
		$this->newField('start');
		$this->newField('end');
		$this->newField('minutes');
	}
}
