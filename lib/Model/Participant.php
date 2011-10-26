<?php
class Model_Participant extends Model_Table {
	public $entity_code='participant';
	public $table_alias='par';

	function defineFields(){
		parent::defineFields();

		$this->newField('user_id')
			->mandatory(true)
            ->refModel('Model_User')
			;

		$this->newField('budget_id')
			->mandatory(true)
            ->refModel('Model_Budget')
			;

        $this->addfield('role')
            ->type('list')->listData(array(
                    'Select...',
                    'manager'=>'Manager',
                    'developer'=>'Developer',
                    'qa'=>'Quality Assurance',
                    'design'=>'Designer',
                    ));


	}
}
