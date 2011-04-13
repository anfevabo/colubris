<?php
class page_team_timesheets extends Page_EntityManager {
	public $controller='Controller_Timesheet';
    public $regexp=null;
	function initMainPage(){


        // Add filter to the right
        $f=$this->add('HtmlElement')->addStyle('float','right')->add('Form',null,null,array('form_empty'));
        $f_filter=$f->addField('reference','f','');
        $m=$f_filter->setModel('TimesheetFilter');
        $f_filter->includeDictionary(array('client_id'));



		parent::initMainPage();
		$g=$this->grid;
        $this->c->addCondition('report_id', null);

        // Bind Grid and Form
        $f_filter->js('change',$g->js()->reload(array('f'=>$f_filter->js()->val())));
        if($_GET['f']){
            $m->loadData($_GET['f']);
            $g->dq->where('title regexp "'.$this->api->db->escape($m->get('regexp')).'"');
        }

		$g->addButton('Import')->js('click')->univ()->dialogURL('Import tasks',$this->api->getDestinationURL('./import'));

		$g->addColumnPlain('expander','convert');

        $g->addButton('Define Filters')->js('click')->univ()->frameURL(
                'Filters',$this->api->getDestinationURL('./filters'),array(
                    'close'=>$f_filter->js()->_enclose()->val('')->change()));

        $this->add('H2')->set('Assign timeshetes with time');

        // Add form for converting results
        $f=$this->add('MVCForm');
        $f_ts=$f->addField('line','ts');

        $f_cli=$f->addField('reference','client_id');
        $f_cli->setModel('Client');
        $f->setModel('Report');

        if($_GET['client_id']){
            $f->getElement('budget_id')->dictionary()->addCondition('client_id',$_GET['client_id']);
            $f->getElement('task_id')->dictionary()->addCondition('client_id',$_GET['client_id']);
        }

        $f_cli->js('change',array(
                $f->js()->atk4_form('reloadField','task_id',array(
                        $this->api->getDestinationURL(),'client_id'=>$f_cli->js()->val()
                        )),
                $f->js()->atk4_form('reloadField','budget_id',array(
                        $this->api->getDestinationURL(),'client_id'=>$f_cli->js()->val()
                        )),
                ));

        $f_sum=$f->getElement('amount')->setAttr('readonly','true');

        // When Filter changes — autofill company
        $f_filter->js(true)->univ()->bindFillInFields(array('client_id'=>$f->getElement('client_id')));

        // When items are selected - call AJAX function to calculate sum of minutes
        $f_ts->js('autochange_manual change')->univ()->ajaxec(array(
                    $this->api->getDestinationURL(),
                    'sum_ids'=>$f_ts->js()->val()
                    ));
        if($_GET['sum_ids']){
            // AJAX will sum the minutes and place into the field
            $ids=join(',',json_decode(stripslashes($_GET['sum_ids'])));

            $q=clone $g->dq;
            $v=$q->field('sum(minutes)')
                ->where('id in',$ids)->do_getOne();
            $f_sum->js()->val($v)->execute();
        }

        if($f->isSubmitted()){
            $this->api->db->beginTransaction();
            try{

                $f->getController()->set($f->getAllData())->addTimesheets(json_decode($f->get('ts')));

                $this->api->db->commit();
                $f->js()->univ()->location($this->api->getDestinationURL())->execute();
            }catch(Exception $e){
                $this->api->db->rollback();
                throw $e;
            }
        }




        $g->addSelectable($f_ts);
	}
    function page_filters(){
        $cr=$this->add('CRUD');
        $cr->setModel('TimesheetFilter');
    }
	function page_import(){
		$f=$this->add('Form');
		$f->add('View_Hint',null,'hint')->set('Why not make your own importer? Fork us on github, then modify
				page/team/timesheets.php file');

		$importers=array(
				'Toggl'=>'Controller_Importer_Toggl',
				'Agile CSV'=>'Controller_Importer_AgileCsv',
				'Test Importer'=>'Controller_Importer_Sample',
				);


		$importer_index=array_keys($importers);
		$f->addField('dropdown','format')
			->setValueList($importer_index);
		$f->addField('text','data');

		if($f->isSubmitted()){
			$key=$importer_index[$f->get('format')];

			$imp_c=$this->add($importers[$key]);

			$count=$imp_c->importFromText($f->get('data'));

			$f->js()->univ()->successMessage('Imported '.$count.' records')->closeDialog()->page($this->api->getDestinationURL('..'))->execute();

		}
	}
	function page_convert(){
		$c=$this->add('Controller_Timesheet_Minco');
		$c->loadData($_GET['id']);
		$this->api->stickyGET('id');


		$rc=$this->add('Controller_Report');
		$rc->getField('user_id')->editable(false);
		$f=$this->add('MVCForm')->setController($rc);

		$f->set('date',$c->get('date'));
		$f->set('result',$c->get('title'));
		$f->set('amount',round($c->get('minutes')/60,2));

		if(!$c->get('user_id'))$rc->set('user_id',$this->api->auth->get('id'));

		if($f->isSubmitted()){
			$f->update();

			$c->update(array('report_id'=>$rc->get('id')));

			$f->js()->univ()->page($this->api->getDestinationURL('team/timesheets'))->execute();
		}
	}
}
