<?php

class Grid_ClientBudget extends MVCGrid {

    public $fancy = null;

    function init_timestamp() {
        if (!$this->fancy

            )$this->fancy = $this->add('Controller_Fancy');
    }

    function format_timestamp($field) {
        $this->current_row[$field] = $this->fancy->fancy_datetime($this->current_row[$field]);
    }

    function format_flink($field) {
        $this->current_row[$field] = '<a class="flink" ' .
                'onclick="$(this).univ().ajaxec(\'' . $this->api->getDestinationURL(null,
                        array($field => $this->current_row['id'], $this->name . '_' . $field => $this->current_row['id'])) . '\')">' .
                $this->current_row[$field] . '</a>';
    }

    function formatRow() {
        $s=$this->current_row['amount_spent'];
        $e=$this->current_row['amount_eur'];
        @$p=$s/$e*100;
        parent::formatRow();
        if($p>95){
            $this->setTDParam('amount_spent','style/color','red');
        }else{
            $this->setTDParam('amount_spent','style/color',null);
        }

        switch($this->current_row['success_criteria']){
            case 0: $this->current_row['amount_spent']='';break;
            case 1: 
                    if($p){
                        if($p>95){
                            $p=round($p-100);
                            $p=$p.'% over budget';
                        }else{
                            $p='on budget';
                        }
                    }else{
                        $p='-';
                    }
                    $this->current_row['amount_spent']=$p;
                    break;
        }
        $f=$this->getModel()->getField('success_criteria')->listData();
        $this->current_row['success_criteria']=$f[$this->current_row['success_criteria']];
            $this->setTDParam('name','style/color','red');
    }

    function calcluateBudgetSpent($id){
        $project = $this->add('Model_Timesheet');


        $project->addCondition('budget_id', $id);
        //$q->field('amount_spent');
         $result = $project->getRows();

        $amount = 0;
        foreach ($result as $row) {
            //var_dump($row);
            //exit;
            if($row['amount_spent']){
                $amount+=$row['amount_spent'];
            }
//            if (is_array($row)) {
//                foreach ($row as $column) {
//                    $amount += $amount;
//                }
//            }
        }
        return $amount;
    }

}
