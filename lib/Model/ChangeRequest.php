<?php
class Model_ChangeRequest extends Model_Requirement {
    function init(){
        parent::init();
        $this->setMasterField('type','chreq');
    }
}
