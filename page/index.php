<?php
class Page_index extends Page {
    function init(){
        parent::init();

        if($this->api->auth->isLoggedIn())$this->api->redirect('home');

        $form=$this->add('Frame')->setTitle('Client Log-in')->add('Form');
        $form->addField('line','email')->js(true)->focus();
        $form->addField('password','password');
        $form->addSubmit('Login');
        $form->setFormClass('vertical');
        $auth=$this->api->auth;
        if($form->isSubmitted()){
            $l=$form->get('email');
            $p=$form->get('password');

            $enc_p = $auth->encryptPassword($p,$l);
            if($auth->verifyCredintials($l,$enc_p)){
                $auth->login($l);
                $form->js()->univ()->redirect('home')->execute();
            }
            $form->getElement('password')->displayFieldError('Incorrect login');
        }
    }
    function defaultTemplate(){
        return array('page/index');
    }
}
