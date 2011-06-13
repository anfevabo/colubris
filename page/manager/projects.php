<?
class page_manager_projects extends Page {
    function initMainPage(){
        $cr=$this->add('CRUD');
        $cr->setModel('Project');
        if($cr->grid){
            $cr->grid->addColumn('template,expander','budgets')
                ->setTemplate('<?$budgets?> budgets');
        }

    }
    function page_budgets(){
        $this->api->stickyGET('project_id');
        $this->add('CRUD')->setModel($this->add('Model_Budget')
                ->addCondition('project_id',$_GET['project_id']),
                null,array('id','name','start_date','deadline','accepted','closed',
                    'amount_eur','amount_spent'));
    }
}
