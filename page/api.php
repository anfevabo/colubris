<?php
class page_api extends Page {
    function init(){
        parent::init();

        if(isset($_REQUEST['hash'])){
            $u=$this->add('Model_User')->getBy('hash',$_REQUEST['hash']);
            if(!$u['id']){
                echo json_encode("Wrong user hash");
                $this->logVar('wrong user hash: '.$v['hash']);
                exit;
            }

            unset($u['password']);
            $this->api->auth->addInfo($u);
            $this->api->auth->login($u['email']);
		}
    }
    function page_quote_create(){
        $m=$this->add('Model_Quote');
        $p=$this->add('Model_Project')->loadData($_REQUEST['project_id']);
        if(!$p->isInstanceLoaded())die('ouch');

        var_Dump($_REQUEST);

        $m->set($_REQUEST)->update();

        $this->response(array('success'=>$m->get('id')));
    }
    function page_project_list(){
        $this->response($this->add('Model_Project')->getRows());
        exit;
    }
    function response($result){
        echo json_encode($result);
        exit;
    }
}
