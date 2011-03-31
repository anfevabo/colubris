<?php
class page_account extends Page {
    function init(){
        parent::init();

        $l=$this->add('Columns');

        // Left side form
        $col=$l->addColumn('50%');
        $f=$col->add('MVCForm');
        $f->setModel($this->api->getUser(),array('name','email'));
        if($f->isSubmitted()){
            $f->update();
            $f->js()->univ()->successMessage('Successfully updated your details')->execute();
        }

        $u=$this->api->getUser();
        $col=$l->addColumn('50%');
		$f=$col->add('Form');
		$f->addField('password','pp','Old Password')->validateNotNULL();
		$f->addField('password','p1','New Password')->validateNotNULL();
		$f->addField('password','p2','Verify');
        $f->addSubmit('Change Password');
		if($f->isSubmitted()){
            if($this->api->auth->encryptPassword($f->get('pp'))!=$u->get('password')){
                $f->getElement('pp')->displayFieldError('Old password is incorrect');
                return;
            }

			if($f->get('p1')!=$f->get('p2')){
                $f->getElement('p2')->displayFieldError('Password don\'t match');
                return;
            }

            $u->set('password',$this->api->auth->encryptPassword($f->get('p1')))->update();
            $f->js()->univ()->successMessage('password changed')->getjQuery()->reload()->execute();
        }
    }
}
