<?php
class Manager_Tasks extends View {
    function init(){
        parent::init();

        $this->add('H4')->set('3. Miscelanious tasks');

        $grid=$this->add('MVCGrid');
        $m=$grid->setModel('Task',array('name','estimate'));
        $m->addCondition('budget_id',null);
    }
}
