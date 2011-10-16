<?php
class Manager_Deadlines extends View {
    function init(){
        parent::init();


        $this->add('H4')->set('4. Project Deadlines');
        $grid=$this->add('MVCGrid');
        $model=$grid->setModel('Budget',array('name','priority','state','deadline'));
        $model->addCondition('accepted',true);
        $model->addCondition('closed',false);
    }
}
