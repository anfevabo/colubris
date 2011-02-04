<?php
class View_WhatToDo extends MVCGrid {
	function init(){
		parent::init();

		$c=$this->add('Controller_Task');
		$c->setActualFields(array('name','estimate','cur_progress','budget_id'));
		$this->setController($c);
	}
}
