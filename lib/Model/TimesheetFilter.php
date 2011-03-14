<?php
class Model_TimesheetFilter extends Model_Table {
    public $entity_code='timesheet_filter';
    function init(){
        parent::init();

        $this->addField('name');
        $this->addField('regexp');
        $this->addField('client_id')
            ->refModel('Model_Client');
        $this->addField('user_id')
            ->refModel('Model_User');

    }
}
