<?
class page_admin_clients extends Page_EntityManager {
	public $controller='Controller_Client';

	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumnPlain('expander','users','Users');
	}
	function page_users(){
		// This implements a quite complete task editing solution. 

		// Preserve screen_id throughout the whole thing
		$this->api->stickyGET('id');

		$cc=$this->add('View_Columns');
		$c1=$cc->addColumn();
		$c2=$cc->addColumn();

		// Create form for editing user
		$c=$this->add('Controller_User');
		$c->setMasterField('client_id',$_GET['id']);
		$c->setActualFields(array('email','name'));
		$f=$c2->add('MVCForm')->setController($c);

		$f->addSubmit($_GET['user_id']?'Update':'Add new');

		if($_GET['user_id']){
			$this->api->stickyGET('user_id');
			$c->loadData($_GET['user_id']);
		}

		$g=$c1->add('MVCGrid')->setController($c);

		// Add 2 extra columns for editing and deletion
		$g->addColumnPlain('button','edit');
		$g->addColumnPlain('delete','delete');

		// If editing buton is clicked, reload form with task_id argument
		if($_GET['edit']){
			$cc->js()->reload(array('user_id'=>$_GET['edit']))->execute();
		}

		if($f->isSubmitted()){
			// If our main form is submitted, then save data and relad both the grid and the form
			$f->update();
			$cc->js()->reload(array('user_id'=>null))->execute();
			//$f->js(null,$g->js()->reload())->reload(array('task_id'=>null))->execute();

		}

	}
}
