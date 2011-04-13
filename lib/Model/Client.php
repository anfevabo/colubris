<?
/*
   Client definition

   */
class Model_Client extends Model_Table {
	public $entity_code='client';
	public $table_alias='cl';

	function defineFields(){
		parent::defineFields();

		// Each field can have a varietty of properties. Please
		// referr to FieldDefinition.php file for more information

		$this->newField('name')
			;

			;

            /*
        $u=$this->api->getUser();
        if($u->get('is_client')){
            $this->addCondition('id',$u->get('client_id'));
        }
        */


	}
}
