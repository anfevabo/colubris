<?php
class Manager_Projects extends View {
    public $quotes,$acceptance;
    function init(){
        parent::init();

        $this->api->stickyGET('id');
        $this->api->stickyGET($this->name);


        $this->add('H4')->set('1. Quotes needed');
        $this->quotes=$grid=$this->add('MVCGrid');
        $m=$grid->setModel('Budget',array('name','priority','state','deadline'));
        $m->addCondition('accepted',false);
        $m->addCondition('closed',false);
        $grid->addColumn('button','supply_quote');
        if($_GET['supply_quote']){
            $this->js()->univ()->dialogURL('Quote information',
                    $this->api->getDestinationURL(null,
                        array($this->name=>'supplyquote','id'=>$_GET['supply_quote'])))
                ->execute();
        }
        if($_GET[$this->name]=='supplyquote')return $this->supplyQuote();

        $this->add('H4')->set('2. Acceptance. Check on client');
        $this->acceptance=$grid=$this->add('MVCGrid');
        $grid->setModel('Budget_Completed',array('name','priority','state','bugs','tasks'));
    }
    function supplyQuote(){

        $v=$this->add('View','supplyquote');
        $_GET['cut_object']=$v->name;

        $m=$this->add('Model_Budget')->loadData($_GET['id']);

        $form=$v->add('MVCForm');
        $form->addField('readonly','budget_name')->set($m->get('name'));
        $form->setModel($m,array('amount','state'));
        $form->getElement('amount')->js('change',
                $form->getElement('state')->js()->val('quotereview')
                );
        $form->getElement('amount')->js(true)->univ()->autoChange(0);

        if($form->isSubmitted()){
            $form->update();

            $form->js()->univ()->location($this->api->getDestinationURL(null,array(
                            $this->name=>null,'id'=>null)))->execute();
        }
    }
}
