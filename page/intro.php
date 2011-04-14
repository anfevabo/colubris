<?php
class page_intro extends Page {
    function init(){
        parent::init();

        $t=$this->add('Tabs');

        $t->addTabURL('./concept','1. What is Budget?');
        $t->addTabURL('./development','2. How Developers Work?');
        $t->addTabURL('./tracking','3. Progress Tracking');
        $t->addTabURL('./timesheets','4. Timesheets');
        $t->addTabURL('./changereq','5. Change Requests');
        $t->addTabURL('./tasks','6. Task Lists');
        $t->addTabURL('./qa','7. Quality Assurance');
    }
}
