<?php
class Controller_Importer_Csv extends Controller_Importer {
	public $task;
	public $field_sequence=array('user','date','project','descr','minutes');


	function init(){
		parent::init();

        if(!$this->api->isManager()){
            $this->task->setMasterField('user_id',$this->api->auth->get('id'));
        }

        // TODO: only manager can use this importer
	}


	function set_user($val){
        if($this->api->isManager()){
            $this->task->set('user_id',$val);
        }
	}
	function set_date($val){
		$this->task->set('date',$val);
	}
	function set_descr($val){
		$this->task->set('title',$val);
	}
	function set_date_irl($val){
        $date=explode('/',$val);
        $date=implode('-',array($date[2],$date[1],$date[0]));
		return $this->set_date($date);
	}
    function set_minutes($val){
        $this->task->set('minutes',$val);
    }
    function set_user_id($val){
        $this->task->set('user_id',$val);
    }
	function set_project($val){
		$p_id=$this->add('Model_Project')->getBy('name',$val);
		exit;
	}
    function set_($val){
        // ignoring
    }

	function importFromText($text){
        $cnt=0;

		if(!($fs_count=count($this->field_sequence))){
			throw new Exception('Field sequence must contain fields');
        }

		$csv=explode("\n",$text);
		foreach($csv as $row){
            $row=trim($row);
            if(!$row)continue;  // skip empty rows

			$row=str_getcsv($row);

			while(count($row)>$fs_count)array_pop($row);
			while(count($row)<$fs_count)$row[]='';


			$data=array_combine($this->field_sequence,$row);
			foreach($data as $key=>$val){
				$this->task->unloadData();
				$fx='set_'.$key;
				$this->$fx($val);

			}

            $this->task->update();
            $cnt++;
		}
        return $cnt;
	}
}
