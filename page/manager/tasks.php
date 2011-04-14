<?php

class page_manager_tasks extends page {
    function init(){

        parent::init();
        $this->add('CRUD')->setModel('Task');
    }
}
