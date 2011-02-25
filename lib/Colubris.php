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
		$m->addMenuItem('Requiremnts','manager/req');	//
		$m->addMenuItem('Clients','admin/clients');
		$m->addMenuItem('Users','admin/users');
		$m->addMenuItem('Files','admin/filestore');

		$m->addMenuItem('about');
		$m->addMenuItem('logout');

		// If you want to use ajax-ify your menu
		// $m->js(true)->_load('ui.atk4_menu')->atk4_menu(array('content'=>'#Content'));


		// HTML element contains a button and a text
		$sc=$this->add('HtmlElement',null,'name')
			->setElement('div')
			->setStyle('width','700px')
			->setStyle('text-align','right');

		// Button have 2 events. Click event opens the frame, reload event is a custom event
		// which would reload parent
		$b=$sc->add('Button')
			->setStyle('float','right')
			->setStyle('margin-left','1em')
			->set('Change Scope');
		$b->js('click')->univ()->dialogURL('Change Scope',$this->api->getDestinationURL('/scope'));
		$b->js('my_reload',$sc->js()->univ()->page($this->api->getDestinationURL()));


		$sc->add('Text')
			->set(
				$this->api->auth->get('name').' @ '.
				'Colubris Team Manager v'.$this->getVersion().'<br/>'.$this->getScope());



		parent::initLayout();
	}
	function page_scope($p){
		$f=$p->add('Form');


		$f->addField('reference','project')
			->emptyValue('All Projects')
			->setValueList($f->add('Model_Project'));

		$f->addField('reference','budget')
			->emptyValue('All Budgets')
			->setValueList($f->add('Model_Budget'));

		$f->addField('reference','client')
			->emptyValue('All Clients')
			->setValueList($f->add('Model_Client'));

		$f->addField('reference','user')
			->emptyValue('All Users')
			->setValueList($f->add('Model_User'));

		$f->set($this->recall('scope',array()));

		if($f->isSubmitted()){

			$this->memorize('scope',$f->getAllData());


			$f->js()->univ()->closeDialog()->getjQuery()->trigger('my_reload')->execute();

		}
	}
	function getScope(){
		$sc=$this->recall('scope',array());
		$t=array();
		foreach($sc as $key=>$val){
			if(!$val)continue;
			try{
				switch($key){
					case'project':
						$t[]='Project: '.$this->add('Model_Project')->loadData($val)->get('name');
						break;
					case'client':
						$t[]='Client: '.$this->add('Model_Client')->loadData($val)->get('name');
						break;
					case'budget':
						$t[]='Budget: '.$this->add('Model_Budget')->loadData($val)->get('name');
						break;
					case'user':
						$t[]='User: '.$this->add('Model_User')->loadData($val)->get('name');
						break;
				}
			}catch(Exception_InstanceNotLoaded $e){
				// Entry was deleted or no longer available
				$sc[$key]=null;
				$this->memorize('scope',$sc);
				$t[]='<font color=red>'.$key.' was reset</font>';
			}
		}
		return join(', ',$t);
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
