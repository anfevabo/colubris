<?php
class page_minco extends Page {
	function init(){
		// title=Title&date=Date&start=Start Time&end=End Time&minutes=Minutes

		// a:5:{s:10:"parameter1";s:14:"colubris: misc";s:10:"parameter2";s:10:"05/02/2011";s:10:"parameter3";s:5:"03:36";s:10:"parameter4";s:5:"03:48";s:10:"parameter5";s:2:"12";}

		parent::init();
		$v=$_GET;if($_POST)$v=$_POST;


		// Figure out user
		$u=$this->add('Controller_User')->getBy('hash',$v['hash']);
		if(!$u['id']){
			echo "Wrong user hash";
			$this->logVar('wrong user hash: '.$v['hash']);
			exit;
		}



		$d=explode('/',$v['date']);
		$v['date']=join('-',array($d[2],$d[1],$d[0]));
		$v['user_id']=$u['id'];

		if(!$v['minutes']){
			echo "Wrong format";
			$this->logVar('wrong format'.serialize($v));
			exit;
		}
		$this->logVar($v['minutes'].' minutes logged');

		$this->add('Controller_Timesheet_Minco')->update($v);
	}
}
