<?
class Model_Model extends Model_Requirement {
    function defineFields(){
        parent::defineFields();
        $this->setMasterField('type','model');
    }
}
