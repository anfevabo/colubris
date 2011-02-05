<?
/*
   Commonly you would want to re-define ApiFrontend for your own application.
 */
class Colubris extends ApiFrontend {
	function init(){
		parent::init();

		$this->showExecutionTime();

		// Keep this if you are going to use database
		$this->dbConnect();

		// Keep this if you are going to use plug-ins
		$this->addLocation('atk4-addons',array(
					'php'=>array('mvc',
						'misc/lib',
						)
					))
			->setParent($this->pathfinder->base_location);

		// Keep this if you will use jQuery UI in your project
		$this->add('jUI');

		// Initialize any system-wide javascript libraries here
		$this->js()
			->_load('atk4_univ')
			// ->_load('ui.atk4_expander')

			;

		// Before going further you will need to verify access
		$auth=$this->add('SQLAuth');
		$auth->setSource('user','email','password')->field('id,name');
		$auth->usePasswordEncryption('md5');
		$auth->allowPage('minco');
		if(!$auth->isPageAllowed($this->api->page))$auth->check();

		// Alternatively 
		// $this->add('MVCAuth')->setController('Controller_User')->check();


		$this->initLayout();
	}
	function initLayout(){

		// If you are using a complex menu, you can re-define
		// it and place in a separate class
		$m=$this->add('Menu','Menu','Menu');
		$m->addMenuItem('Dashboard','index');

		$m->addMenuItem('Timesheets','team/timesheets');		// Team members enter their reports here
		$m->addMenuItem('Status','client/status');		// Clients can follow project status here

		$m->addMenuItem('Projects','admin/projects');	// Admin can setup projects and users here
		$m->addMenuItem('Budgets','admin/budgets');	// Admin can setup projects and users here
		$m->addMenuItem('Screens','admin/screens');	//
		$m->addMenuItem('Clients','admin/clients');
		$m->addMenuItem('Users','admin/users');
		$m->addMenuItem('Files','admin/filestore');

		$m->addMenuItem('about');
		$m->addMenuItem('logout');

		// If you want to use ajax-ify your menu
		// $m->js(true)->_load('ui.atk4_menu')->atk4_menu(array('content'=>'#Content'));

		$this->template->trySet('name',

				$this->api->auth->get('name').' @ '.
				'Colubris Team Manager v'.$this->getVersion());

		parent::initLayout();
	}

	function getVersion(){
		return '0.1';
	}
	function getCurrentTask(){
		$task_id=$this->api->recall('task',null);
		if(!$task_id)return null;

		$t=$this->add('Controller_Task')->loadData($task_id);
		if(!$t->isInstanceLoaded()){
			$this->forget('task');
			return null;
		}
		return $t;
	}

	function page_pref($p){

		// This is example of how you can use form with MVC support
		$p->frame('Preferences')->add('MVCForm')
			->setController('Controller_User');
	}
}
