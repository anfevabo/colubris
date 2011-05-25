<?php
class ReportGrid extends MVCGrid {
    public $fancy=null;

    function init_timestamp(){
        if(!$this->fancy)$this->fancy=$this->add('Controller_Fancy');
    }
    function format_timestamp($field){
        $this->current_row[$field]=$this->fancy->fancy_datetime($this->current_row[$field]);
    }

	function format_flink($field){
		$this->current_row[$field]='<a class="flink" '.
		'onclick="$(this).univ().ajaxec(\''.$this->api->getDestinationURL(null,
			array($field=>$this->current_row['id'],$this->name.'_'.$field=>$this->current_row['id'])).'\')">'.
			$this->current_row[$field].'</a>';
	}
}
