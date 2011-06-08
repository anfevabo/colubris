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
        function formatRow() {
        $days_spent = (float) $this->current_row['days_spent'];
        $quoted = (float) $this->current_row['mandays'];

        if ($days_spent && $quoted) {
           $difference = round((float) ( $days_spent/$quoted ) *100, 2);

           if($difference>100){
                $difference = '<div style="background:red;">' . $difference . '</div>';
           }
                $this->current_row['difference']=$difference;
        }
        if($days_spent){
            $this->current_row['days_spent']=round($this->current_row['days_spent'],2);
        }
        $time = strtotime($this->current_row['deadline']);

        if ($time < time()) {
            $this->current_row['deadline'] = "<div style='background-color:red;'>" . $this->current_row['deadline'] . "</div>";
        }
    }
}
