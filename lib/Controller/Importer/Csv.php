<?php
class Controller_Importer_Csv extends Controller_Importer {
	public $task;
	public $field_sequence=array('user','date','project','descr','minutes');

	function set_user($val){
		// admins only - check for user correspondence fist
		//$this->task->set('user_id',
	}
	function set_date($val){
		$this->task->set($val);
	}
	function set_project($val){
		$p_id=$this->add('Model_Project')->getBy('name',$val);
		var_dump('project=',$p_id);
		exit;
	}

	function importFromText($text){

		if(!($fs_count=count($this->field_sequence)))
			throw new Exception('Field sequence must contain fields');
		$csv=explode("\n",$text);
		foreach($csv as $row){
			$row=str_getcsv($row);

			while(count($row)>$fs_count)array_pop($row);
			while(count($row)<$fs_count)$row[]='';


			$data=array_combine($this->field_sequence,$row);
			foreach($data as $key=>$val){
				$this->task->unloadData();
				$fx='set_'.$key;
				$this->$fx($val);

				echo "combined!";
				exit;
			}

		}
		/*
		$this->task
			->set('minutes','5')
			->set('date',date('Y-m-d'))
			->set('title',$text)
			->update();
			*/
	}
}
