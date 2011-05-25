<?
class page_manager_clients extends Page_EntityManager {
	public $controller='Controller_Client';

	function initMainPage(){
		parent::initMainPage();

		$this->grid->addFormatter('name','fullwidth');
		$this->grid->addColumnPlain('expander','users','Users');
	}
	function page_users(){
        $this->api->stickyGET('client_id');
        $m=$this->add('Model_User')
            ->setMasterField('client_id',$_GET['client_id'])
            ;

        $cr=$this->add('CRUD');
        $cr->setModel($m,array('email','name','hourly_cost','daily_cost'));
        if($cr->grid){
            $cr->grid->addColumn('button','reset','Reset Password');

            if($_GET['reset']){
                $m->loadData($_GET['reset']);
                $m->resetPassword();

                $cr->grid->js()->univ()->successMessage('New password sent to email: "'.$m->get('email').'"')->execute();

            }
        }
        
	}
}
