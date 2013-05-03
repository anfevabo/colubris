<?php

class page_manager_timesheets extends Page {

    function initMainPage() {
        $quicksearch = $this->add('ReportQuickSearch', null, null, array('form/quicksearch'));
        $crud = $this->add('CRUD_Export');
        $timesheet=  $this->add('Model_Timesheet');
        //$timesheet->addCondition('is_closed','N');
        $m = $crud->setModel($timesheet, array('title', 'user','budget', 'user_id', 'budget_id', 'date', 'minutes','is_closed'));
        if ($grid = $crud->grid) {
            $grid->addButton('Import')->js('click')->univ()->dialogURL('Import',
                    $this->api->getDestinationURL('./import'));
            $grid->addPaginator(50);
            $grid->addTotals();
            $grid->dq->order('date desc,id desc');
            // $f->useDQ($grid->dq);

            $grid->last_column = 'budget';
            $grid->makeSortable();
            $grid->last_column = 'title';
            $grid->makeSortable();
            $grid->last_column = 'user';
            $grid->makeSortable('user');
            $grid->last_column = 'minutes';
            $grid->makeSortable();

            $crud->grid->addQuickSearch(array('title'),'ReportQuickSearch');
            //$quicksearch->useGrid($grid)->useFields(array('title'));
            //$quicksearch = $grid->addQuickSearch(array('title'), 'ReportQuickSearch');//useGrid($grid)->useFields(array('title'));
            //$quicksearch=$this->add('ReportQuickSearch',null,null,array('form/quicksearch'));

            $this->add('H3')->set('Change Selected');
            $f = $this->add('Form')->setFormClass('horizontal');
            $f_sel = $f->addField('line', 'sel');
            $grid->addSelectable($f_sel);

            $ts = $f->setModel('Timesheet', array('client_id', 'project_id', 'budget_id', 'requirement_id', 'task_id'));

            if ($f->isSubmitted()) {
                $q = $ts->dsql();
                if ($f->get('budget_id')
                    )$q->set('budget_id', $f->get('budget_id'));
                //if($f->get('budget_id')$q->set('budget_id',$f->get('budget_id'));

                $ids = json_decode(stripslashes($f->get('sel')));

                $q->where('id in', $ids);

                $q->do_update();
                $grid->js()->reload()->execute();
            }
        }else {
            $crud->form->set('date', date('Y-m-d'));
            /* $crud->onSubmit(function() use($crud){
              });
             */
            $crud->form->onSubmit(function($f) use ($crud) {
                        $f->memorize('budget_id', $f->get('budget_id'));
                        $f->memorize('user_id', $f->get('user_id'));
                    });
            $crud->form->set('budget_id', $crud->form->recall('budget_id', null));
            $crud->form->set('user_id', $crud->form->recall('user_id', null));
        }
    }

    function page_import() {
        $f = $this->add('Form');
        $f->add('View_Hint', null, 'hint')->set('Why not make your own importer? Fork us on github, then modify
                page/team/timesheets.php file');

        $importers = $this->api->pathfinder->searchDir('php',
                        'Controller/Importer');
        $importers = str_replace('.php', '', $importers);
        $importers = array_combine($importers, $importers);

        $f->addField('dropdown', 'format')
                ->setValueList($importers);
        $f->addField('text', 'data');
        $f->addSubmit('Import');

        $f->onSubmit(function($f) use($importers) {

                    if (!in_array($f->get('format'), $importers)) {
                        throw $f->exception('No Such Importer', 'ValidityCheck')->setField('format');
                    }


                    $imp_c = $f->add('Controller_Importer_' . $f->get('format'));

                    $count = $imp_c->importFromText($f->get('data'));

                    return $f->js()->univ()->successMessage('Imported ' . $count . ' records')
                            ->closeDialog()->location($f->api->getDestinationURL('..'));
                });
    }

}

class ReportQuickSearch extends QuickSearch {

    function init() {
        parent::init();

        //$this->setFormClass('horizontal');
        $this->addField('checkbox', 'no_budget', 'n/b');
        $this->addField('autocomplete/basic', 'user_id', 'U: ')->setModel('Developer');
        $budget=$this->add('Model_Budget');
        $budget->addCondition('closed',false);
        $this->addField('autocomplete/basic', 'budget_id', 'B: ')->setModel($budget);
        $this->addField('DatePicker', 'from', 'Date: ')->setAttr('style', 'width: 100px');
        $this->addField('DatePicker', 'to', '-')->setAttr('style', 'width: 100px');
    }

    function applyDQ($q) {
        if ($this->get('no_budget')
            )$q->where('isnull(budget_id)');
        if ($this->get('from')
            )$q->where('date>=', $this->get('from'));
        if ($this->get('to')
            )$q->where('date<=', $this->get('to'));
        if ($this->get('user_id')
            )$q->where('user_id', $this->get('user_id'));
        if ($this->get('budget_id')
            )$q->where('budget_id', $this->get('budget_id'));
        parent::applyDQ($q);
    }

}
