<?
class Model_Service extends Model_Requirement {
    function init(){
        parent::init();
        $this->setMasterField('type','chreq');
    }
}
