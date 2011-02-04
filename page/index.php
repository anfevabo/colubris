<?php
class Page_index extends Page {
	function init(){
		parent::init();
		$this->add('View_Hint')->set('Welcome to Colubris. Platform for team and client collaboration');

		$c=$this->add('View_Columns');

		$c1=$c->addColumn();
		$c1->add('H3')->set('Project Operations');
		$c1->add('P')->set('As administrator you can perform a number of tasks with projects');
		$c1->add('Button')->set('New Project Wizard')->js('click')->univ()->redirect('/admin/newproj');

		$c2=$c->addColumn();

		$c2->add('H2')->set('You should work on:');
		$c2->add('View_WhatToDo');
	}
}
