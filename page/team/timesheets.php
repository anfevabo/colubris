<?php
class page_team_timesheets extends Page_EntityManager {
	public $controller='Controller_Timesheet';
	function initMainPage(){
		parent::initMainPage();
		//$c=$this->add('Controller_Timesheet');
		//$g=$this->add('MVCGrid')->setController($c);
		$g=$this->grid;

		$g->addButton('Import')->js('click')->univ()->dialogURL('Import tasks',$this->api->getDestinationURL('./import'));

		$g->addColumnPlain('expander','convert');

	}
	function page_import(){
		$f=$this->add('Form');
		$f->add('View_Hint',null,'hint')->set('Why not make your own importer? Fork us on github, then modify
				page/team/timesheets.php file');

		$importers=array(
				'Test Importer'=>'Controller_Importer_Sample'
				);


		$importer_index=array_keys($importers);
		$f->addField('dropdown','format')
			->setValueList($importer_index);
		$f->addField('text','data');

		if($f->isSubmitted()){
			$key=$importer_index[$f->get('format')];

			$imp_c=$this->add($importers[$key]);

			$imp_c->importFromText($f->get('data'));

			$f->js()->univ()->closeDialog()->page($this->api->getDestinationURL('..'))->execute();

		}
	}
	function page_convert(){
		$c=$this->add('Controller_Timesheet_Minco');
		$c->loadData($_GET['id']);
		$this->api->stickyGET('id');


		$rc=$this->add('Controller_Report');
		$rc->getField('user_id')->editable(false);
		$f=$this->add('MVCForm')->setController($rc);

		$f->set('date',$c->get('date'));
		$f->set('result',$c->get('title'));
		$f->set('amount',round($c->get('minutes')/60,2));

		if(!$c->get('user_id'))$rc->set('user_id',$this->api->auth->get('id'));

		if($f->isSubmitted()){
			$f->update();

			$c->update(array('report_id'=>$rc->get('id')));

			$f->js()->univ()->page($this->api->getDestinationURL('team/timesheets'))->execute();
		}
	}
}
