<?
class page_manager_budgets extends Page {

	//public $controller='Controller_Budget';

	function initMainPage(){
        $c=$this->add('CRUD');
        $c->setModel('Budget');

        if($c->grid){
            $c->grid->addColumnPlain('expander','team','Team Access');
            $c->grid->addColumnPlain('expander','scope','Budget Scope');
            $c->grid->getController()->scopeFilter($c->grid->dq);
        }

	}

	function page_team(){
		$g=$this->add('MVCGrid');
	}

	function page_scope(){
		$this->add('View_Hint')->set('Scope defines what works will be performed inside this budget');

		$c=$this->add('Controller_Screen');$c->addCondition('budget_id',$_GET['id']);
		$this->add('MVCGrid')->setController($c);

		$c=$this->add('Controller_Task');$c->addCondition('budget_id',$_GET['id']);
		$this->add('MVCGrid')->setController($c);

		$c=$this->add('Controller_Report');$c->addCondition('budget_id',$_GET['id']);
		$this->add('MVCGrid')->setController($c);
	}
}
