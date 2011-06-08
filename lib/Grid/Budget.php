<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Grid_Budget extends MVCGrid {
    function formatRow() {
        parent::formatRow();
        $days = '';
        $mandays = (float) $this->current_row['mandays'];
        $days_spent = (float) $this->current_row['days_spent'];
        if ($mandays && $days_spent) {
            $days = ($days_spent/$mandays)*100;
        }
        if ($days != '' && $days > 100) {
            $days = '<div style="background:red;">' . $days . '</div>';
        }
        $this->current_row['depleted'] = $days;
        return $this->current_row;
    }

}
