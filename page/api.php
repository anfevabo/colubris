<?php
class page_api extends Page {
    function page_project_list(){
        echo json_encode($this->add('Model_Project')->getRows());
        exit;
    }
}
