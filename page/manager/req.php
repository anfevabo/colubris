<?
class page_manager_req extends Page {
    public $pc,$bc;
    function init(){
        parent::init();

		$sc=$this->api->recall('scope',array());

		if(!$sc['budget']){
			$this->add('View_Error')->set('You should choose budget before adding requirements');
			return;
		}

		$this->bc=$bc=$this->add('Model_Budget')->loadData($sc['budget']);
		$this->pc=$pc=$bc->getRef('project_id'); //$this->add('Model_Project')->loadData($sc['project']);



    }
	function initMainPage(){

		// Use scope selector to make sure only a single 
		$sc=$this->api->recall('scope',array());

		if(!$sc['budget']){
			$this->add('View_Error')->set('You should choose budget before adding requirements');
			return;
		}

		$bc=$this->add('Model_Budget')->loadData($sc['budget']);
		$pc=$bc->getRef('project_id'); //$this->add('Model_Project')->loadData($sc['project']);


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

		$g->addButton('New Screen')
            ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL('./add',array('new'=>'Screen')));
		$g->addButton('New Model')
            ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL('./add',array('new'=>'Model')));
		$g->addButton('Service')
            ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL('./add',array('new'=>'Service')));
		$g->addButton('Change Request')
            ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL('./add',array('new'=>'ChangeRequest')));
		$g->addButton('Bugfixes')
            ->js('click')->univ()->dialogURL('New Screen',$this->api->getDestinationURL('./add',array('new'=>'Bugfix')));

		// Highlight row
		if($id=(int)$this->api->recall('req')){
			//$g->find('tbody tr[rel='.$id.']'')II//
		}


		$id=$this->api->recall('req');
		if($id){
            if($g=$c2->add('View_RequirementDetails')
                    ->loadData($id,true)===false)
                $this->forget('req');
        }
	}
    function page_add(){
        $this->api->stickyGET('new');
        $f=$this->add('MVCForm');
        $f->setModel($_GET['new']);
        $f->set('budget_id',$this->bc->get('id'));
        $f->set('project_id',$this->pc->get('id'));

        if($f->isSubmitted()){
            $f->update();
            $f->js()->univ()->closeDialog()->page($this->api->getDestinationURL('..',array('new'=>null)))->execute();
        }
        
    }
}
?>
