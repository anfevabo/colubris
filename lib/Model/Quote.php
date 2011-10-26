<?php
class Model_Quote extends Model_Table {
    public $entity_code = "quote";
    function init(){
        parent::init();
        $this->addField('project_id')->refModel('Model_Project');
        $this->addField('name')->caption('Reference');
        $this->addField('amount')->type('money');
        $this->addField('issued')->type('date');

    }
}
