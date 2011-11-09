<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CRUD_ManagerBudgets extends CRUD {
    public $grid_class='Grid_ManagerBudget';
    function setModel($a,$b,$c){
        parent::setModel($a,$b,$c);
        $c=$this;
        if ($c->grid) {
            $c->grid->addColumn('profit','profit');

            $c->grid->addColumn('template,expander', 'team', 'Team')->setTemplate('<?$team?> people');

            $c->grid->addColumn('expander', 'scope', 'Specs');
            $c->grid->addColumn('expander', 'timeline', 'Timeline');
            $c->grid->addColumn('expander', 'finances', 'Finances');

            $c->grid->model->scopeFilter($c->grid->dq);
            $c->grid->addOrder()->move('profit','after','amount_spent')->now();

            unset($c->grid->columns['project']);
            unset($c->grid->columns['client']);
        }
        if($c->form){
            $c->form->setFormClass('basic atk-form-basic-2col');
            $c->form->add('Order')
                ->move($c->form->addSeparator(),'before','amount')
                ->now();
        }
    }
}

