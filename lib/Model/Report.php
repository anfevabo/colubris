<?
class Model_Report extends Model_Table {
	public $entity_code='report';
	public $table_alias='R';

	function defineFields(){
		parent::defineFields();

		//$this->newField('task_id')->refModel('Model_Task');

		$this->newField('budget_id')->refModel('Model_Budget');

		$u=$this->newField('user_id')->refModel('Model_User');
        $u->defaultValue($this->api->getUserID());
        $lu = $this->api->getUser();
        if ($lu->isInstanceLoaded()){
            $admin = $lu->get("is_admin");
        } else {
            $admin = false;
        }
        if(!$admin){
            $u->system(true);
            $this->setMasterField('user_id',$this->api->getUserID());
        }

		$this->newField('date')->datatype('date')->mandatory(true)->defaultValue(date('Y-m-d'));
		$this->newField('result')->datatype('text');
		$this->newField('amount')->datatype('int')->mandatory(true)->caption('Total Minutes');

	}
	public function toStringSQL($source_field, $dest_fieldname, $expr = 'name') {
        $expr='date';
		return '(select '.$expr.' from '.$this->entity_code.
			   '  where id = '.$source_field.') as '.$dest_fieldname;
	}
    function addTimesheets($ids){
        $ts=$this->add('Model_Timesheet');
        $q=$ts->dsql('browse');
        $q
            ->where('id in',$ids)
            ->where('report_id',null)
            ;
        $sum=$q->field('sum(minutes)')->do_getOne();
        if(is_null($sum))$sum=0;
        $this->set('amount',$sum);
        $this->update();

        $q->set('report_id',$this->get('id'))->do_update();
    }
}
