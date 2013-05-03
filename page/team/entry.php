<?php
class page_team_entry extends Page {
    function init(){
        parent::init();


    }
    function page_manual(){
        $m=$this->add('Model_Timesheet');
        $m->setMasterField('user_id',$this->api->auth->model['id']);
        $inf=$this->add('misc/InfiniteAddForm');
        $inf->setModel($m,array('title','date','minutes','budget_id'));

        $fld=$inf->form->getElement('date');
        
        $next=explode('_',$fld->name);
        $next[count($next)-2]++;
        $next=implode('_',$next);

        $prev=explode('_',$fld->name);
        $pr=--$prev[count($prev)-2];
        $prev=implode('_',$prev);

        if($pr)$fld->js(true)->val($fld->js()->_selector('#'.$prev)->val());
        $fld->js('change',$fld->js()->_selector('#'.$next)->val($fld->js()->val()));

        $fld=$inf->form->getElement('budget_id');
        
        $next=explode('_',$fld->name);
        $next[count($next)-3]++;
        $next=implode('_',$next);
        $prev=explode('_',$fld->name);
        $prev[count($prev)-3]++;
        $prev=implode('_',$prev);

        if($pr)$fld->js(true)->val($fld->js()->_selector('#'.$prev)->val());
        $fld->js('change',$fld->js()->_selector('#'.$next)->val($fld->js()->val()));
    }
    function page_import(){
        $f=$this->add('Form');
        $f->add('View_Hint',null,'hint')->set('Why not make your own importer? Fork us on github, then modify
                page/team/timesheets.php file');

        $importers=$this->api->pathfinder->searchDir('php',
                'Controller/Importer');
        $importers=str_replace('.php','',$importers);
        $importers=array_combine($importers,$importers);

		$f->addField('dropdown','format')
			->setValueList($importers);
		$f->addField('text','data');
        $f->addSubmit('Import');

		$f->onSubmit(function($f) use($importers){

			if(!in_array($f->get('format'),$importers)){
                throw $f->exception('No Such Importer','ValidityCheck')->setField('format');
            }
                

			$imp_c=$f->add('Controller_Importer_'.$f->get('format'));

			$count=$imp_c->importFromText($f->get('data'));

			return $f->js()->univ()->successMessage('Imported '.$count.' records');//->execute();//->closeDialog()->page($f->api->getDestinationURL('..'))->execute();

		});
	}
    
    function initMainPage(){
        $tt=$this->add('Tabs');
        $t=$tt->addTab('Basics');
        $v=$t->add('View',null,null,array('view/time_entry_info'));
        $t=$tt->addTabURL('./manual','Manual Entry');
        $t=$tt->addTabURL('./import','Import');


        $url=$this->api->getDestinationURL('autotime',array('hash'=>$this->api->auth->model['hash']));
        $url->useAbsoluteURL();
        $v->template->trySet('url',$url);

        $minco_url=$this->api->getDestinationURL('minco',array('hash'=>$this->api->auth->model['hash']));
        $minco_url->useAbsoluteURL();
        $v->template->trySet('minco_url',$minco_url);

    }
}
