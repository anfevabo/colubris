<?php

class page_manager_tasks extends Page {
    function init(){

        parent::init();
        $this->add('CRUD')->setModel('Task');
    }
}
