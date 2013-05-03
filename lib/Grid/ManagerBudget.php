<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Grid_ManagerBudget extends Grid {
    function formatRow() {

        $deadline=$this->current_row['deadline'];

        parent::formatRow();

        // Deadline
        $interval=$this->api->formatter->formatDeadline($deadline,$this->current_row['state']);
        if(!is_null($interval))$this->current_row['deadline'].='<br/><small>'.$interval.'</small>';

        $this->current_row['priority']=$this->api->formatter->formatPriority($this->current_row['priority']);

          $days_spent = (float) $this->current_row['days_spent'];
        $days_spent_lastweek = (float) $this->current_row['days_spent_lastweek'];
        if ($days_spent) {
            $this->current_row['days_spent'] = round($this->current_row['days_spent'], 2);
        } else {
            $this->current_row['days_spent'] = 0;
        }
        if ($days_spent_lastweek) {
            $this->current_row['days_spent_lastweek'] = round($this->current_row['days_spent_lastweek'], 2);
        } else {
            $this->current_row['days_spent_lastweek'] = 0;
        }
        $this->current_row['name']=
            $this->current_row['name'].' ('.$this->current_row['project'].')<br/><small>'.
            $this->current_row['client'].'</small>';

        return $this->current_row;
    }
    function format_profit($field){
        $this->current_row[$field]=$m=@round(
            $this->current_row['amount_spent_original']/
            $this->current_row['amount_eur_original']*100).'%';

		if($m>100){
			$this->setTDParam($field,'style/color','red');
		}else{
			$this->setTDParam($field,'style/color',null);
		}
    }
}
