<?php

class page_manager_rfq extends Page {
    function page_index(){

        $this->add('H1')->set('New Request for Quotation');

        $form=$this->add('Form');

        $client=$form->addField('autocomplete','client_id');
        $client->setModel('Client');
        $client->add('Button_NewEntry',null,'after_field')
            ->setLabel('New Client')->setModel('Client',array('name'));

        $project=$form->addField('autocomplete','project_id');
        $project->setModel('Project');

    }

    function page_init(){
        $form=$this->add('Form');
        $form->addField('line','name');

        $form->addSubmit();

        if($form->isSubmitted()){
            $form->js()->univ()->addTab(
                    $this->api->getDestinationURL('../step2'),
                    'Step 2'
                    )->execute();
        }
    }

    function page_step2(){
        $form=$this->add('Form');
        $form->addField('line','name');
        $form->addButton('Cancel')->js('click')->univ()->closeThisTab();
    }
}

class DynamicTab extends View_Tabs_jUItabs {
    function init(){
        View_Tabs::init();
        $this->js(true)
            ->univ()->dynamictabs();
        $this->tab_template=$this->template->cloneRegion('tabs');
        $this->template->del('tabs');
    }
}
