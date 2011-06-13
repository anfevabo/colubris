<?php
class Model_Payment_Me extends Model_Payment {
    function init(){
        parent::init();

        $this->addField('total_tm_hours')->calculated(true)->type('real')
            ->caption('Hours This Month')->sortable(true);

        // TODO: add Relation and fetch field from budget
        //$this->addRel
    }
    function calculate_total_tm_hours(){
        return $this->getTimesheets()
            ->where('month(T.`date`)=month(now())')
            ->field('round(sum(T.minutes/60),1)')
            ->select();
    }
}
