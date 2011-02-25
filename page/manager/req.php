<?
class page_manager_req extends Page {
	function init(){
		parent::init();

		// Use scope selector to make sure only a single 
		$sc=$this->api->recall('scope',array());

		if(!$sc['budget'] || !$sc['project']){
			$this->add('View_Error')->set('You should choose both project and budget before adding requirements');
			return;
		}

		$bc=$this->add('Model_Budget')->loadData($sc['budget']);
		$pc=$this->add('Model_Project')->loadData($sc['project']);


		$this->add('Hint')->set('You are now entering requirements for the project <b>'.$pc->get('name').'</b>. Those
				requirements will be implemented and funded from the budget <b>'.$bc->get('name').'</b>
				');

		// 2 columns layout

		$cc=$this->add('Columns');

		// Left column for high level requirements
		$c1=$cc->addColumn('50%');
		$c1->add('H2')->set('High-level requierements');

		// Rigth-column will depend on type
		$c2=$cc->addColumn('50%');

		$g=$c1->add('MVCGrid');
		$m=$g->setModel('Requirement',array('name','type','sub_estimate','estimate','tot_estimate'));
		$g->addColumn('button','select');

		if($_GET['select']){
			$this->api->memorize('req',$_GET['select']);
			$c2->js()->reload()->execute();
		}

		$m->scopeFilter($g->dq);

		$g->addButton('New Screen');
		$g->addButton('New Model');
		$g->addButton('Service');
		$g->addButton('Change Request');
		$g->addButton('Bugfixes');

		// Highlight row
		if($id=(int)$this->api->recall('req')){
			//$g->find('tbody tr[rel='.$id.']'')II//
		}


		$id=$this->api->recall('req');
		if($id){
			$g=$c2->add('View_RequirementDetails')
				->loadData($id);
		}
		/*
		$m=$g->setModel('Task',array('name','estimate'));
		$g->addTotals();
		$g->addButton('Add Sub-Task');
		*/

	}
}
?>
