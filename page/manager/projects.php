<?
class page_manager_projects extends Page {
    function page_index(){
        $cr=$this->add('CRUD');
        $cr->setModel('Project');
        if($cr->grid){
            $cr->grid->addColumn('template,expander','budgets')
                ->setTemplate('<?$budgets?> budgets');
            $cr->grid->addColumn('template,expander','quotations')
                ->setTemplate('<?$quotations?> quotations');
        }

    }
    function page_budgets(){
        $this->api->stickyGET('project_id');
        $this->add('CRUD')->setModel($this->add('Model_Budget')
                ->addCondition('project_id',$_GET['project_id']),
                null,array('id','name','start_date','deadline','accepted','closed',
                    'amount_eur','amount_spent'));
    }
    function page_quotations(){
        $this->api->stickyGET('project_id');

        $m=$this->add('Model_Quote')->setMasterField('project_id',$_GET['project_id']);

        $this->add('CRUD')->setModel($m);
    }
}
