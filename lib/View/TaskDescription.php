<?php
class View_TaskDescription extends View {
	function init(){
		parent::init();
		$this->add('Button',null,'status_button')->set('resolve');

	}
	function setController($c){
		parent::setController($c);

		if($c->get('screen_id')){
			$this->add('View_Screen',null,'screen_info')
				->setController($c->getRef('screen_id'));
		}

	}
	function defaultTemplate(){
		return array('view/taskdescription');
	}
	function render(){
		$this->template->set($this->getController()->get());
		return parent::render();
	}
}
