<?
class page_admin_users extends Page_EntityManager {
	public $controller='Controller_User';
	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumnPlain('expander','projects');
	}
}
