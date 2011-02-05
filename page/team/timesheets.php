<?php
class page_team_timesheets extends Page {
	function initMainPage(){
		$c=$this->add('Controller_Timesheet_Minco');
		$g=$this->add('MVCGrid')->setController($c);

		$g->addColumnPlain('expander','convert');

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
