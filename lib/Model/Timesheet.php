<?
class Model_Timesheet extends Model_Table {
	public $entity_code='timesheet';
	public $table_alias='T';
	function defineFields(){
		parent::defineFields();

		$this->newField('title');
		$this->newField('user_id')->refModel('Model_User');
		$this->newField('report_id')->refModel('Model_Report','amount');
		$this->newField('date')->datatype('date');
		$this->newField('start');
		$this->newField('end');
		$this->newField('minutes');

	}
}
