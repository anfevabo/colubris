<?php
class page_client extends Page {
    function init(){
        parent::init();

        $t=$this->add('H1');
        $t->set('Welcome Client');


        $c=$this->api->getClient();

        $this->add('CRUD')->setModel($c);
    }
}
