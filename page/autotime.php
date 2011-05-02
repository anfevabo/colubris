<?php
class page_autotime extends Page {
	function init(){

		parent::init();
		$v=$_GET;if($_POST)$v=$_POST;


		// Figure out user
		$u=$this->add('Model_User')->getBy('hash',$v['hash']);
		if(!$u['id']){
			echo "Wrong user hash";
			$this->logVar('wrong user hash: '.$v['hash']);
			exit;
		}



		$d=explode('/',$v['date']);
		$v['date']=join('-',array($d[2],$d[1],$d[0])).' '.date('H:i:s');
		$v['user_id']=$u['id'];

		if(!$v['minutes']){
			echo "Wrong format";
			$this->logVar('wrong format'.serialize($v));
			exit;
		}
		$this->logVar($v['minutes'].' minutes logged');

		$this->add('Controller_Timesheet_Minco')->update($v);
		echo "success";
		exit;
	}
}
