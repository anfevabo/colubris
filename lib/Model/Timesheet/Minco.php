<?
class Model_Timesheet_Minco extends Model_Timesheet {
	function defineFields(){
		parent::defineFields();

		$this->newField('notes')->datatype('text');
		$this->newField('url');
		$this->newField('calendar');
	}
}
