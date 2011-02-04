<?php
class View_WhatToDo extends MVCGrid {
	function init(){
		parent::init();

		$c=$this->add('Controller_Task');
		$c->setActualFields(array('name','status','estimate','screen_id','cur_progress'));
		$this->setController($c);
	}
}
