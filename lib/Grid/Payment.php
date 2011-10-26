<?php
/**
  * Payment is an item line on the budget invoice. It links to the participant in the project and groups their timesheets
  *  by the top level of specification break-down.
  */
class Grid_Payment extends MVCGrid {
    function init(){
        parent::init();
    }
    function setModel($m,$x){
        $m=parent::setModel($m,$x);
        $this->removeColumn('hourly_rate');
        $this->removeColumn('expense');
        $this->removeColumn('service_name');
        $this->removeColumn('sell_rate');
        $this->removeColumn('sell_total');
        $this->removeColumn('sell_total');
        return $m;
    }
    function formatRow(){
        parent::formatRow();

        $x='';
        if($this->current_row['expense']+0){
            $x=$this->current_row['expense'];
            if($this->current_row['hourly_rate'])$x.=' and ';
        }
        if($this->current_row['hourly_rate']){
            $x.='<font color="red">'.$this->current_row['hourly_rate'].'</font>/h';
        }

        $this->current_row['user'].='<br/><small>Paying '.$x.'</small>';

        $x='';
        if($this->current_row['sell_total']+0){
            $x=$this->current_row['sell_total'];
            if($this->current_row['sell_rate'])$x.=' and ';
        }
        if($this->current_row['sell_rate']){
            $x.='<font color="red">'.$this->current_row['sell_rate'].'</font>/h';
        }
        if($x)$x='Charging '.$x;else $x='<font color="red">Not charging</font>';
        $this->current_row['total_client_pays'].='<br/><small>'.$x.'</small>';
    }
}
