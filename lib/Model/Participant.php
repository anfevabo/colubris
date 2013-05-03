<?php
class Model_Participant extends Model_Table {
    public $table='participant';

    function init(){
        parent::init();

	$this->addField('user_id')
            ->mandatory(true)
            ->refModel('Model_User');

        $this->addField('budget_id')
            ->mandatory(true)
            ->refModel('Model_Budget');

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
