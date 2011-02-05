<?php
class View_WhatToDo extends MVCGrid {
	public $zoom=5;
	function init(){
		parent::init();

		$c=$this->add('Controller_Task');
		$c->getField('estimate')->system(true);
		$c->getField('cur_progress')->system(true);
		$c->setActualFields(array('name','status','screen_id'));
		$this->setController($c);

		$this->addColumnPlain('progress_txt','progress_txt','pr');
		$this->addColumnPlain('progress','progress');
	}
	function format_progress_txt($field){
		if(!$this->current_row['cur_progress']){
		   $this->current_row[$field]=(int)$this->current_row['estimate'];
		   return ;
		}
		$this->current_row[$field]=(int)$this->current_row['cur_progress'].' of '.(int)$this->current_row['estimate'];
	}
	function format_progress($field){
		// https://chart.googleapis.com/chart?cht=bhs&chs=200x20&chd=t:10|100&chco=4d89f9,c6d9fd&chbh=10
		$esc=min(($e=(int)$this->current_row['estimate'])*$this->zoom,100);
		$c=$this->current_row['cur_progress'];

		if($e && $c<=$e){
			$pct=(int)($c/$e*100);
			$this->current_row[$field]='<img width="'.$esc.'" height="20" src="http://chart.googleapis.com/chart?cht=bhs&chs='.$esc.'x20&chd=t:'.$pct.'|100&chco=4d89f9,c6d9fd&chbh=10"/>';
		}
		if($e && $c>$e){

			$siz=(int)max($e,$c-$e);  // size 


			$pct=(int)($e/$siz*100);
			$pct_ov=(int)(($c-$e)/$siz*100);

			$siz=min($siz*$this->zoom,100);

			$this->current_row[$field]='<img width="'.$siz.'" height="20" src="http://chart.googleapis.com/chart?cht=bhs&chs='.
				$siz.'x20&chd=t:'.$pct.','.$pct_ov.'&chco=4d89f9|ff0000,c6d9fd&chbh=5"/>';
			                                                                  //https://chart.googleapis.com/chart?cht=bhs&chs=200x20 &chd=t:10,30|100,0&chco=4d89f9|ff0000,c6d9fd&chbh=5

		}
	}
}
