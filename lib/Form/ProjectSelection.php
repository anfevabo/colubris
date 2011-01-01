<?
class Form_ProjectSelection extends Form {
	function init(){
		parent::init();

		$c=$this->add('Controller_Project');

		$pr=$this->addField('reference','pr','Project')->setController($c);

		$pr->set($this->api->recall('cur_proj'));
		$pr->js('change',$this->js()->submit());

		if($this->isSubmitted()){
			$this->api->memorize('cur_proj',$this->get('pr'));

			// Reloads all elements which
			$this->js()->_selector('.pr_change_bind')->trigger('pr_change')->execute();
		}
	}
	function limitTourrentProject($v){
		$v->js(true)->addClass('pr_change_bind');
		$v->js('pr_change')->reload();
		$v->getController()->addCondition('project_id',$this->api->recall('cur_proj'));
	}
	function defaultTemplate(){
		return array('form_empty');
	}
}
