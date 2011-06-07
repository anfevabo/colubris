<?php

class page_team extends Page {

    function initMainPage() {
        $ac_url = $this->api->locateURL('js', 'autocomplete/ui.autocomplete.css');

        //$this->api->template->append('js_include',
        //       '<link rel="stylesheet" type="text/css" href="'.$ac_url.'" />');


        $g = $this->add('ReportGrid', null, 'just_reported');
        $m = $g->setModel('Timesheet', array('user', 'budget', 'title', 'date', 'minutes'));
        //      $m->addCondition('user_id',$this->api->getUserID());
        $g->dq->order('date desc,id desc');
        $g->dq->where('date>now()-interval 1 week');
        $g->addPaginator(10);

        /* left column data */
        $model = $this->add("Model_Timesheet");
        $result = $model->getHoursToday($this->api->getUserID());
        $this->template->trySet('htd', $result);


        $d = $this->add('Model_Developer')->loadData($this->api->getUserID());
        $target = $d->get('weekly_target');

        $result = $model->getHoursWeekly($this->api->getUserID());
        $this->template->trySet('htw', $result);
        $status = $model->status($result, $target);
        $this->template->trySet('htw_status', $status);


        $result = $model->getHoursMonthly($this->api->getUserID());
        $this->template->trySet('htm', $result);
        $status = $model->status($result, $target, 'monthly');
        $this->template->trySet('htm_status', $status);

        /* end left column data */
        
        $m = $this->add('Model_Developer_Stats');
        $m->setDateRange(date('Y-m-d', strtotime('last monday', strtotime('sunday'))), date('Y-m-d'));
        $data = $m->getRows(array('id', 'name', 'hours_today'));
        $result = array();
        foreach ($data as $row) {
            $row['name'] = preg_replace('/ .*/', '', $row['name']);
            $result[$row['name']] = $row['hours_today'] + 0;
        }

        $d = $this->add('Model_Developer')->loadData($this->api->getUserID());
        $data = $d->getTimesheets()->dsql()
                        ->field('unix_timestamp(date) d,minutes/60')
                        ->order('date')
                        ->where('week(date)-if(weekday(date)=6,1,0)=week(now())')
                        ->do_getAssoc();

        $min = strtotime('last monday', strtotime('sunday')) * 1000;
        $max = strtotime('-2 days', strtotime('sunday')) * 1000;
        $max = strtotime('+20 hours', strtotime('-2 days', strtotime('sunday'))) * 1000;


        $result = array(array($min, 100));
        $target = $d->get('weekly_target');


        foreach ($data as $key => $row) {
            $target-=$row;
            $result[] = array(
                (int) $key * 1000, // milis
                max(round($target / $d->get('weekly_target') * 100), -50)
            );
        }

        $data = $result;


        //array_walk($data,function(&$row){$row=array((int)$row;});
        $ch = $this->add('Chart', null, 'graph');
        $ch->setDefaultType('line');
        $ch->setHeight('200');
        $ch->set('title', array('text' => 'Hours Per Day'));
        $ch->set('xAxis', array('type' => 'datetime', 'dateTimeLabelFormats' => array('day' => '%e')));
        $ch->set('yAxis', array('max' => 100, 'min' => 0, 'title' => null, 'labels' => array()));
        $ch->series(array('name' => 'Hours to target', 'data' => $data));
        $ch->series(array('name' => 'Baseline', 'data' => array(array($min, 100), array($max, 0))));
        $ch->series(array('name' => 'Today', 'data' => array(array(time() * 1000 - 100, 100), array(time() * 1000, 0))));
    }

    function page_amount() {

        $this->api->stickyGET('id');

        $t = $this->add('Tabs');

        $g = $t->addTab('Breakdown')
                        ->add('ReportGrid');
        $g->setModel('Timesheet')
                ->addCondition('report_id', $_GET['id']);
        $g->addTotals();

        $t->addTab('Amend')
                ->add('FormAndSave')->setModel('Report', array('user_id', 'client_id', 'budget_id', 'date', 'amount'))->loadData($_GET['id']);

        $t->addTab('Delete')
                ->add('FormDelete')->setModel('Report', array(''))->loadData($_GET['id']);
        ;
    }

    function defaultTemplate() {
        if ($this->api->page == 'team'

            )return array('page/team');
        return parent::defaultTemplate();
    }

}
