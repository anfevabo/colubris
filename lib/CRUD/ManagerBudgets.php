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
            $c->grid->addColumnPlain('template,expander', 'team', 'Team')->setTemplate('<?$team?> people');
            $c->grid->addColumnPlain('expander', 'scope', 'Budget Scope');
            $c->grid->getController()->scopeFilter($c->grid->dq);
            $c->grid->addOrder()->move('profit','after','amount_spent')->now();
        }
        if($c->form){
            $c->form->setFormClass('basic atk-form-basic-2col');
            $c->form->add('Order')
                ->move($c->form->addSeparator(),'before','amount')
                ->now();
        }


    }
}

