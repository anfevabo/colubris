<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Customlib_BudgetGrid extends MVCGrid {
    function formatRow() {
        parent::formatRow();
        $days = '';
        $mandays = (float) $this->current_row['mandays'];
        $cur_mandays = (float) $this->current_row['cur_mandays'];
        if ($mandays && $cur_mandays) {
            $days = $mandays/$cur_mandays;
        }
        if ($days != '' && $days > 100) {
            $days = '<div style="background:red;">' . $days . '</div>';
        }
        $this->current_row['depleted'] = $days;
        return $this->current_row;
    }

}
