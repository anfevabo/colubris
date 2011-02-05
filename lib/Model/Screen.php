<?
class Model_Screen extends Model_Table {
	public $entity_code='screen';
	public $table_alias='scr';

	function defineFields(){
		parent::defineFields();

		$this->addField('name');
		$this->addField('ref');
		$this->addField('descr')->datatype('text');

		$this->addField('budget_id')
			->refModel('Model_Budget');

		$this->addField('project_id')
			->refModel('Model_Project');

		$this->addField('filestore_file_id')
			->refModel('Model_Filestore_File')
			->datatype('image');
	}
}
