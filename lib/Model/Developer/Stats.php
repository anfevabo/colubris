<?php
class Model_Developer_Stats extends Model_Developer {
    public $dr=array();
    function init(){
        parent::init();

        $this->addField('hours_today')
            ->calculated(true);
    }
    function setDateRange($from,$to){
        $this->dr=array($from,$to);
    }
    function applyDateRange($q){
        if(!$this->dr)$this->dr=array(date('Y-m-d'),date('Y-m-d'));
        $q->where('date(date)>=',$this->dr[0]);
        $q->where('date(date)<=',$this->dr[1]);
    }
    function calculate_hours_today(){
        $q=$this->add('Model_Timesheet')->dsql();

        $q->where('user_id=u.id')
            ->field('sum(minutes)/60')
            ;
        $this->applyDateRange($q);
        return $q->select();
    }
}
