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
        $days_spent = (float) $this->current_row['days_spent'];
        $days_spent_lastweek = (float) $this->current_row['days_spent_lastweek'];
        $quoted = (float) $this->current_row['mandays'];


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
        if (!$quoted) {

            $this->current_row['mandays'] = 0;
        }
        if (!(float) $this->current_row['amount_eur']) {

            $this->current_row['amount_eur'] = 0;
        }
        $this->current_row['mandays'] = '<div align="center">' . $this->current_row['mandays'] . '</div>';
        $this->current_row['accepted'] = '<div align="center">' . $this->current_row['accepted'] . '</div>';
        $this->current_row['closed'] = '<div align="center">' . $this->current_row['closed'] . '</div>';
        $this->current_row['amount_eur'] = '<div align="right">' . $this->current_row['amount_eur'] . '</div>';
        $this->current_row['days_spent'] = '<div align="center">' . $this->current_row['days_spent'] . '</div>';
        $this->current_row['days_spent_lastweek'] = '<div align="center">' . $this->current_row['days_spent_lastweek'] . '</div>';
    }

}
