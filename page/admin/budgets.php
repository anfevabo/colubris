<?
class page_admin_budgets extends Page_EntityManager {
	public $controller='Controller_Budget';

	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumnPlain('expander','team','Team Access');
		$this->grid->addColumnPlain('expander','scope','Budget Scope');

		$this->c->scopeFilter($this->grid->dq);
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
	}
}
