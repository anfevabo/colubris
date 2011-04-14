<?php
class Model_Developer extends Model_User {
    function init(){
        parent::init();

        $this->addCondition('is_developer',true);

        $this->addField('timesheets_tw')->calculated(true);
        $this->addField('reports_tw')->calculated(true);
    }

    function reportsDQ(){
        return $this->add('Model_Report')->dsql()
            ->where('user_id=u.id')
            ;
    }
    function timesheetsDQ(){
        return $this->add('Model_Timesheet')->dsql()
            ->where('user_id=u.id')
            ;
    }
    function calculate_timesheets_tw(){
        return 

            'round(('.
                        $this->timesheetsDQ()
                        ->field('sum(minutes)')
                        ->where('date>date(DATE_ADD(now(), INTERVAL(2-DAYOFWEEK(now())) DAY))')
                        ->select()
                        .')/60,2)';
    }
    function calculate_reports_tw(){
        return 

            'round(('.
                        $this->reportsDQ()
                        ->field('sum(amount)')
                        ->where('date>date(DATE_ADD(now(), INTERVAL(2-DAYOFWEEK(now())) DAY))')
                        ->select()
                        .')/60,2)';
    }
}
