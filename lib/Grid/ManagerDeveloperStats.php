<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Grid_ManagerDeveloperStats extends MVCGrid {

    function formatRow() {
        parent::formatRow();
//        $days = '';
        $weekly_target = (float) $this->current_row['weekly_target'];
        $hours_lastweek = (float) $this->current_row['hours_lastweek'];
        $hours_week = (float) $this->current_row['hours_week'];
        $hours_month = (float) $this->current_row['hours_month'];
        $hours_lastmonth = (float) $this->current_row['hours_lastmonth'];
        if ($weekly_target) {
            if ($hours_week) {
                $day = date('w');
                if ($day == 6 || $day == 0) {
                    $day = 5;
                }
                $act_target = ($weekly_target / 5) * $day;
                if ($act_target > $hours_week) {
                    $this->current_row['hours_week'] = '<div style="background:red;">' . $this->current_row['hours_week'] . '</div>';
                }
            }
            if ($hours_lastweek) {

                $act_target = $weekly_target;
                if ($act_target > $hours_lastweek) {
                    $this->current_row['hours_lastweek'] = '<div style="background:red;">' . $this->current_row['hours_lastweek'] . '</div>';
                }
            }
            if ($hours_month) {
                $working_days = $this->calculateWorkingDaysInMonth();

                $act_target = $hours_month / ($weekly_target / 5);
                if ($act_target < $working_days) {
                    $this->current_row['hours_month'] = '<div style="background:red;">' . $this->current_row['hours_month'] . '</div>';
                }
            }
            if ($hours_lastmonth) {
                $working_days = $this->calculateWorkingDaysInMonth('', '', true);
                $act_target = $hours_lastmonth / ($weekly_target / 5);
                if ($act_target < $working_days) {
                    $this->current_row['hours_lastmonth'] = '<div style="background:red;">' . $this->current_row['hours_lastmonth'] . '</div>';
                }
            }
        }

        return $this->current_row;
    }

    function calculateWorkingDaysInMonth($year = '', $month = '', $last=false) {
        if ($year == '') {
            $year = date('Y');
        }
        if ($month == '') {
            $month = date('m');
        }

        $startdate = strtotime($year . '-' . $month . '-01');
        $enddate = time();
        if ($last) {
            $enddate = strtotime($year . '-' . $month . '-01');
            $month = $month - 1;
            if ($month == 0) {

                $month = 1;

                $year = $year - 1;
                $startdate = strtotime($year . '-' . $month . '-01');
            } else {

                $startdate = strtotime($year . '-' . $month . '-01');
            }
        }




        $currentdate = $startdate;

        $days = 0;
        while ($currentdate <= $enddate) {

            if ((date('D', $currentdate) == 'Sat') || (date('D', $currentdate) == 'Sun')) {

            } else {
                $days = $days + 1;
            }

            $currentdate = strtotime('+1 day', $currentdate);
        } //end date walk loop
        //return the number of working days
        return $days;
    }

}