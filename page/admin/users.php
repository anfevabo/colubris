<?
class page_admin_users extends Page_EntityManager {
	public $controller='Controller_User';
	function initMainPage(){
		parent::initMainPage();

		$this->grid->addColumnPlain('expander','projects');
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
}
