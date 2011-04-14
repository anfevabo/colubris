<?
class page_manager_projects extends page {
    function init(){
        parent::init();
        $this->add('CRUD')->setModel('Project');
	//public $controller='Controller_Project';
    }
}
