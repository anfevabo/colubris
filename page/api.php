<?php
class page_api extends Page {
    function init(){
        parent::init();

        if(isset($_REQUEST['hash'])){
            $u=$this->add('Model_User')->getBy('hash',$_REQUEST['hash']);
            if(!$u['id']){
                echo "Wrong user hash";
                $this->logVar('wrong user hash: '.$v['hash']);
                exit;
            }

            unset($u['password']);
            $this->api->auth->addInfo($u);
            $this->api->auth->login($u['email']);
		}
    }
    function page_project_list(){
        $this->response($this->add('Model_Project')->getRows());
        exit;
    }
    function response($text){
        echo json_encode($text);
        exit;
    }
}
