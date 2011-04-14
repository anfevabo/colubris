<?php
class ReportGrid extends MVCGrid {

	function format_flink($field){
		$this->current_row[$field]='<a class="flink" '.
		'onclick="$(this).univ().ajaxec(\''.$this->api->getDestinationURL(null,
			array($field=>$this->current_row['id'],$this->name.'_'.$field=>$this->current_row['id'])).'\')">'.
			$this->current_row[$field].'</a>';
	}
    function defaultTemplate(){
        return array('grid_striped');
    }
}
