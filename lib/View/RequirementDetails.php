<?php
class View_RequirementDetails extends HtmlElement {
	function loadData($id){
		$m=$this->add('Model_Requirement');
		$m->loadData($id);

		$this->add('H3')->set('req'.$m->get('id').': '.$m->get('name')." (".$m->get('type').")");
		switch($m->get('type')){

			case'screen':
				$this->add('HtmlElement')
					->setElement('image')
					->setAttr('width','300')
					->setAttr('height','300')
					->setStyle('float','right')
					->set('')
					;

				$this->add('H4')->set('Description:');
				$this->add('Text')
					->set($m->get('descr'));

				// For screen we need to show the image then list tasks
				$this->add('HtmlElement')
					->set('')
					->setStyle('margin-bottom','-30px')
					->addClass('clear');

				$g=$this->add('MVCGrid')
					->addTotals();

				$g
					->setModel('Task',array('name','priority','estimate'))
					->addCondition('screen_id',$m->get('id'));
				$g->addButton('New Task');
				$g->addColumn('expander','edit');
			
		}

	}
}
