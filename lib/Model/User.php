<?
/*
   Model class defines structure and behavor of your model. You can re-define number of existing
   functions and add your own functions. 

   */
class Model_User extends Model_Table {
	public $entity_code='user';
	public $table_alias='u';

	function init(){
		parent::init();

		// Each field can have a varietty of properties. Please
		// referr to FieldDefinition.php file for more information

		$this->addField('email')
			->mandatory(true)
			;

		$this->addField('name')
			;

		$this->addField('password')
            ->system(true)
			;

		$this->addField('client_id')
			->refModel('Model_Client');

		$this->addField('is_admin')->datatype('boolean');
		$this->addField('is_manager')->datatype('boolean');
		$this->addField('is_developer')->datatype('boolean');
		$this->addField('is_client')->datatype('boolean')->calculated(true);

		$this->addField('hash');//->visible(false);

		// You can define related tables through
		// $this->addRelatedEntity()
		// see function comments inside Model/Table

        if($this->api->page!='minco' && $this->api->auth->get('is_admin')!='Y'){
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
    function resetPassword(){

    }
}
