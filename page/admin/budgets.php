<?
class page_admin_budgets extends Page_EntityManager {
	public $controller='Controller_Budget';

	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumnPlain('expander','team');
	}

	function page_team(){
		echo $_GET['id'];

	}
}
