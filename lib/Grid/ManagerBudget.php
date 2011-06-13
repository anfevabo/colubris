<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Grid_ManagerBudget extends MVCGrid {
    function formatRow() {
        parent::formatRow();
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
