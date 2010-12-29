<?
/*
   Client definition

   */
class Model_Client extends Model_Table {
	public $entity_code='client';

	function defineFields(){
		parent::defineFields();

		// Each field can have a varietty of properties. Please
		// referr to FieldDefinition.php file for more information

		$this->newField('name')
			;

			;
	}
}
