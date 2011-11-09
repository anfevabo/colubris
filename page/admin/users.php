<?
class page_admin_users extends Page_EntityManager {
	public $controller='Controller_User';
	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumn('expander','projects');
        $this->grid->addColumn('button','login');
        if($_GET['login']){
            // load user data
            $m=$this->grid->getController();
            $m->loadData($_GET['login']);
            $this->js()->univ()->alert($m->get('email'))->execute();
        }
	}
	function page_edit(){
		parent::page_edit();

		$f=$this->add('Form');
		$f->addField('password','p1','New Password')->validateNotNULL();
		$f->addField('password','p2','Verify');
		$f->addSubmit('Change');
		if($f->isSubmitted()){
			if($f->get('p1')==$f->get('p2')){
				$this->c->set('password',$this->api->auth->encryptPassword($f->get('p1')))->update();

				$f->js()->univ()->successMessage('password changed')->getjQuery()->reload()->execute();



			}else $f->js()->univ()->alert('passwords don\'t match')->execute();
		}
	}
    function page_projects(){
        $this->api->stickyGET('user_id');
        $m=$this->add('Model_Participant')->setMasterField('user_id',$_GET['user_id']);
        $this->add('CRUD')->setModel($m,array('budget_id','hourly_cost','daily_cost'));
    }
}
