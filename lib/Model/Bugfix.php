<?php
class Model_Bugfix extends Model_Requirement {
    function defineFields(){
        parent::defineFields();
        $this->setMasterField('type','bugfix');
    }
}
