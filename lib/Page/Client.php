<?
class Page_Client extends Page {
	public $project_selection_form=null;
	function init(){
		parent::init();


		$this->project_selection_form=
		$p=$this->add('Form_ProjectSelection');
		$p->js(true)->css('float','right');



	}
	function limitTourrentProject($v){
		return $this->project_selection_form->limitTourrentProject($v);
	}
}
