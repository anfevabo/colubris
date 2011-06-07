<?php

class Model_Developer_Stats extends Model_Developer {

    public $dr = array();

    function init() {
        parent::init();
//
//        $this->addField('hours_today')
//                ->caption()
//                ->calculated(true);
//
//        $this->addField('hours_lastday')
//                ->calculated(true);

        $this->addField('hours_week')
                ->caption('This Week')
                ->calculated(true);
        $this->addField('hours_lastweek')
                ->caption('Last Week')
                ->calculated(true);

        $this->addField('hours_month')
                ->caption('This Month')
                ->calculated(true);
        $this->addField('hours_lastmonth')
                ->caption('Last Month')
                ->calculated(true);
    }

    function setDateRange($from, $to) {
        $this->dr = array($from, $to);
    }

    function applyDateRange($q) {
        if (!$this->dr
            )$this->dr = array(date('Y-m-d'), date('Y-m-d'));
        $q->where('date(date)>=', $this->dr[0]);
        $q->where('date(date)<=', $this->dr[1]);
    }

    function calculate_hours_today() {
        return $this->add('Model_Timesheet')
                ->dsql()
                ->where('T.user_id=u.id')
                ->field('sum(minutes)/60')
                ->where('date>now() - interval 1 day')
                ->select();
    }

    function calculate_hours_lastday() {
        $q = $this->add('Model_Timesheet')->dsql();

        $q->where('T.user_id=u.id')
                ->field('sum(minutes)/60');
        $q->where('date<now() - interval 1 day');
        $q->where('date>now() - interval 2 day');
        return $q->select();
    }
    function calculate_hours_week() {
        return $this->add('Model_Timesheet')
                ->dsql()
                ->where('T.user_id=u.id')
                ->field('round(sum(minutes)/60)')
                ->where('date>now() - interval 1 week')
                ->select();
    }

    function calculate_hours_lastweek() {
        $q = $this->add('Model_Timesheet')->dsql();

        $q->where('T.user_id=u.id')
                ->field('round(sum(minutes)/60)');
        $q->where('date<now() - interval 1 week');
        $q->where('date>now() - interval 2 week');
        return $q->select();
    }

     function calculate_hours_month() {
        return $this->add('Model_Timesheet')
                ->dsql()
                ->where('T.user_id=u.id')
                ->field('round(sum(minutes)/60)')
                ->where('date>now() - interval 1 week')
                ->select();
    }

    function calculate_hours_lastmonth() {
        $q = $this->add('Model_Timesheet')->dsql();

        $q->where('T.user_id=u.id')
                ->field('round(sum(minutes)/60)');
        $q->where('date<now() - interval 1 week');
        $q->where('date>now() - interval 2 week');
        return $q->select();
    }

}
