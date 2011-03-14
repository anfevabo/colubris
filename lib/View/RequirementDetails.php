<?php
class View_RequirementDetails extends HtmlElement {
    function addNew(){
        $l=$this->add('LoremIpsum');
        $_GET['cut_object']=$l->name;
    }


	function loadData($id){
		$m=$this->add('Model_Requirement');
		$m->loadData($id);
        if(!$m->isInstanceLoaded())return false;


		$this->add('H3')->set('req'.$m->get('id').': '.$m->get('name')." (".$m->get('type').")");

        $this->add('Button')->setLabel('Edit Basic Info')
            ->js('click')->univ()->dialogURL('Edit Basic Info',
                    $this->api->getDestinationURL('./edit',array('requirement_id'=>$id)));
        $this->add('Button')->setLabel('Delete Requirement')
            ->js('click')->univ()->confirm('Delete "'.$m->get('name').'"')->ajaxec(
                    $this->api->getDestinationURL(null,array($this->name=>'delete')));

        if($_GET[$this->name]=='delete'){
            $n=$m->get('name');
            $m->delete();
            $this->js()->univ()->successMessage('"'.$n.'" deleted')->page($this->api->getDestinationURL())->execute();
        }

        if($_GET[$this->name]=='new')return $this->addNew();

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
					->setModel('Task',array('name','priority','client_id','estimate'))
					->addCondition('screen_id',$m->get('id'));
				$g->addButton('New Task');
				$g->addColumn('expander','edit');

                break;
			

			case'model':

				$this->add('H4')->set('Description:');
				$this->add('Text')
					->set($m->get('descr'));

				// For screen we need to show the image then list tasks
				$this->add('HtmlElement')
					->set('')
					->setStyle('margin-bottom','10px')
					->addClass('clear');

				$g=$this->add('MVCGrid')
					->addTotals();

				$g
					->setModel('Task',array('name','priority','estimate'))
					->addCondition('screen_id',$m->get('id'));

				$g->addButton('+field')
                    ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL(null,array(
                                    $this->name=>'new')));
                $g->addButton('+relation')
                    ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL(null,array(
                                    $this->name=>'new')));
                $g->addButton('+calculated field')
                    ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL(null,array(
                                    $this->name=>'new')));
                $g->addButton('+method')
                    ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL(null,array(
                                    $this->name=>'new')));
                $g->addButton('+controller')
                    ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL(null,array('new'=>'Screen')));


				$g->addColumn('expander','edit');

                break;
		}

	}
}
