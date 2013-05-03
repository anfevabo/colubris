<?php
class Button_NewEntry extends Button {
    public $field=null;
    public $label=null;
    public $model=null;
    public $fields=null;
    public $form=null;
    function setText($t){
        $this->label=$t;
        return parent::setText($t);
    }
    function setModel($m,$fields=null){
        if(is_null($this->label))
            throw $this->exception('Execute setLabel() before setModel() on this button');

        $this->model=$m;
        $this->fields=$fields;

        if($this->owner instanceof Form_Field){
            $this->field=$this->owner;
        }

        if($_GET[$this->name]=='click')return $this->dialog();

        if($this->isClicked()){
            $this->js()->univ()->dialogURL($this->label,
                    $this->api->getDestinationURL(null,array(
                            $this->name=>'click'))
                    )->execute();
        }
    }
    function dialog(){
        $this->api->stickyGET($this->name);
        $v=$this->owner->add('View',$this->short_name.'_dlg',$this->spot);
        $_GET['cut_object']=$v->name;
        $this->form=$form=$v->add('Form');
        $form->setModel($this->model,$this->fields);


        if($form->isSubmitted()){
            $m=$form->update();
            $js=$form->js()->univ()
                ->closeDialog();
            if($this->field){
                $js
                ->getjQuery()
                ->closest('.atk-form')
                ->atk4_form('setFieldValue',$this->field->short_name,$m->get('id'))
                ;
            }
            $js->execute();
        }
    }
}

