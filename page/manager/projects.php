<?
class page_manager_projects extends Page {
    function init(){
        parent::init();
        $this->add('CRUD')->setModel('Project');
	//public $controller='Controller_Project';
    }
}
