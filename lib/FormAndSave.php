<?php
class FormAndSave extends MVCForm {
    function init(){
        parent::init();

        $this->onSubmit(function($f){
            $f->update();

            $f->js()->univ()->successMessage('Saved')->closeDialog()->execute();
        });
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
