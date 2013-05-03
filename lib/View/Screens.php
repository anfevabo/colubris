<?
class View_Screens extends Lister {
	function init(){
		parent::init();

		$this->setController('Controller_Screen');

		$this->addTotals();
	}
	function defaultTemplate(){
		return array('view/screens');
	}
}
