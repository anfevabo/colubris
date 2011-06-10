<?php

class page_client_timesheets extends Page {

    function initMainPage() {
        $quicksearch = $this->add('ReportQuickSearch', null, null, array('form/quicksearch'));
        $crud = $this->add('CRUD_ExportReadOnly');
        $m = $crud->setModel('Timesheet', array('title', 'user', 'budget', 'user_id', 'budget_id', 'date', 'minutes','amount_spent'));
        if ($grid = $crud->grid) {
            $grid->addButton('Import')->js('click')->univ()->dialogURL('Import',
                    $this->api->getDestinationURL('./import'));
            $grid->addPaginator(50);
            $grid->addTotals();
// get project budgets
// end
            $budget_ids = $quicksearch->getBudgetIds();
            $u = $this->api->getUser();
            if (!$u->get('is_admin')) {
                if ($budget_ids) {
                    $grid->dq->where('budget_id in', $budget_ids);
                } else {
                    $grid->dq->where('budget_id ', -9999);
                }
            }
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
            $grid->last_column = 'amount_spent';
            $grid->makeSortable();

            //$crud->grid->addQuickSearch(array('title'),'ReportQuickSearch');
            $quicksearch->useGrid($grid)->useFields(array('title'));
            //$quicksearch = $grid->addQuickSearch(array('title'), 'ReportQuickSearch');//useGrid($grid)->useFields(array('title'));
            //$quicksearch=$this->add('ReportQuickSearch',null,null,array('form/quicksearch'));

            $this->add('H3')->set('Change Selected');
            $f = $this->add('MVCForm')->setFormClass('horizontal');
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
        $this->addField('autocomplete', 'user_id', 'U: ')->setModel('Developer');
        $budget = $this->add('Model_Budget');
        // $budget_ids=  $this->getBudgetIds();
        $u = $this->api->getUser();
        if (!$u->get('is_admin')) {
            $budget_ids=  $this->getBudgetIds();
            if ($budget_ids) {
                $budget->addCondition('id in', $budget_ids);
            } else {
                $budget->addCondition('id ', -9999);
            }
        }

        $this->addField('autocomplete', 'budget_id', 'B: ')->setModel($budget);
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
    function getBudgetIds() {
        $u = $this->api->getUser();
        $project = $this->add('Model_Project');
        $q = $project->dsql();

        $q->where('client_id', $u->get('client_id'));
        $q->field('id');
        $result = $q->do_getAll();


        $ids = array();
        foreach ($result as $row) {
            if (is_array($row)) {
                foreach ($row as $column) {
                    $ids[] = $column;
                }
            }
        }



        if (count($ids) == 0) {
            return false;
        } else {
            $budget = $this->add('Model_Budget');
            $bq = $budget->dsql();
            $bq->where('project_id in', implode(',', $ids));
            $bq->field('id');
            $result = $bq->do_getAll();
            $budget_ids = array();
            foreach ($result as $row) {
                if (is_array($row)) {
                    foreach ($row as $column) {
                        $budget_ids[] = $column;
                    }
                }
            }
            if (count($budget_ids) == 0) {
                return false;
            } else {
                return implode(',', $budget_ids);
            }

            //$m->addCondition('project_id in', implode(',', $ids));
        }
        return false;
    }

}
