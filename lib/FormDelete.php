<?php
class FormDelete extends MVCForm {
    function init(){
        parent::init();
        $this->add('P')->set('Are You sure you want to delete this?');



        $this->onSubmit(function($f){
            $f->getController()->delete();

            $f->js()->univ()->successMessage('Deleted')->closeDialog()->execute();
        });
    }
    function setModel($m,$junk=null){
        $m=parent::setModel($m,array(''));
        $this->getElement('Save')->set('Delete');
        return $m;
    }
    function onSubmit($callback){
        $this->addHook('submit',$callback);
    }

    function submitted(){
        if(!parent::submitted())return false;

        try{
            $this->hook('submit',array($this));
        }catch (Exception_ValidityCheck $e){
            $f=$e->getField();
            if($f && is_string($f) && $fld=$this->hasElement($f)){
                $fld->displayFieldError($e->getMessage());
            } else $this->js()->univ()->alert("Undefined field $f caused error: ".$e->getMessage())->execute();
        }
        return true;
    }
}



