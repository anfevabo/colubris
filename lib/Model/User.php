<?
/*
   Model class defines structure and behavor of your model. You can re-define number of existing
   functions and add your own functions. 

   */
class Model_User extends Model_Table {
	public $entity_code='user';
	public $table_alias='u';

	function defineFields(){
		parent::defineFields();

		// Each field can have a varietty of properties. Please
		// referr to FieldDefinition.php file for more information

		$this->newField('email')
			->mandatory(true)
			;

		$this->newField('name')
			;

		$this->newField('password')
			->visible(false)
			->editable(false)
			;

		$this->newField('client_id')
			->refModel('Model_Client');

		$this->newField('is_admin')->datatype('boolean');
		$this->newField('is_manager')->datatype('boolean');
		$this->newField('is_developer')->datatype('boolean');
		$this->newField('is_client')->datatype('boolean')->calculated(true);

		$this->newField('hash');//->visible(false);

		// You can define related tables through
		// $this->addRelatedEntity()
		// see function comments inside Model/Table

        if($this->api->auth->get('is_admin')!='Y'){
            $this->addCondition('id',$this->api->auth->get('id'));
        }

	}
	function beforeInsert(&$d){
		$d['hash']=md5(uniqid());

		return parent::beforeInsert($d);
	}
	function calculate_is_client(){
		return 'if(client_id is null,"N","Y")';
	}
}
