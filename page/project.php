<?php
class page_project extends Page {
    public $b;
    function init(){
        parent::init();

        //$this->add('View_WhatToDo');

        $p=$this->add('Model_Project')->loadData($_GET['project_id']);
        $this->api->stickyGET('project_id');

        $this->template->set($p->get());

        $morework=$this->add('Form',null,'MoreWork');
        $morework->addField('text','more','');
        $morework->setFormClass('vertical');
        $morework->addSubmit('Request a Quotation');

        // fill in budgets

        $this->b=$this->add('Model_Budget_Active');
        $budgets=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($budgets as $budget){
            $b=$this->add('Budget',null,'Budget',array('page/project','Budget'));
            $b->set($budget);
        }

        if(!$budgets)$this->template->trySet('Budget','No Jobs');


        // Completed Jobs
        $this->b=$this->add('Model_Budget_Completed');
        $budgets=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($budgets as $budget){
            $b=$this->add('Budget',null,'Completed',array('page/project','Completed'));
            $b->set($budget);
        }

        if(!$budgets)$this->template->trySet('Completed','No Jobs');


        // Quotes
        $this->b=$this->add('Model_Quote');
        $quotes=$this->b->addCondition('project_id',$p->get('id'))->getRows();
        foreach($quotes as $quote){
            $b=$this->add('Quote',null,'Quote',array('page/project','Quote'));
            $b->set($quote);
        }

        if(!$quotes)$this->template->trySet('Quote','No Quotes');



    }
    function defaultTemplate(){
        return array("page/project");
    }
}
class Budget extends View {

    function getWorkingDays($startDate,$endDate){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        return $workingDays;
    }



    function set($f){

        $a=$this->owner->b->getField('state')->listData();
        $f['status']=$a[$f['state']]?:$f['state'];

        $f['mpct']=round($f['amount_paid']/$f['amount']*100);

        $days=($this->getWorkingDays($f['start_date'],date('Y-m-d')));
        $total=($this->getWorkingDays($f['start_date'],$f['deadline']));

        $f['tpct']=round($days/$total*100);

        $this->template->set($f);
    }
}
class Link extends HtmlElement {
    function init(){
        parent::init();
        $this->href('#');
        $this->setElement('a');
    }
    function href($url){
        $this->setAttr('href',$url);
        return $this;
    }
}
class Quote extends View {
    function set($f){
        $this->template->set($f);

        $this->add('Link',null,'Details')->set('Detailed Breakdown')
            ->js('click')->univ()->newWindow($this->api->getDestinationURL(null,array($this->name=>'details')),null,"width=800,height=600");

        if($_GET[$this->name]=='details'){
            $m=$this->add('Model_Quote')->loadData($f['id']);
            echo $m->get('html');
            echo '<center><input type=button onclick="window.close()" value="Close"/></center>';
            exit;
        }
        //$this->template->set('details',$this->api->getDestinationURL());
    }

}
