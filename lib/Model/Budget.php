<?
class Model_Budget extends Model_Table {
	public $entity_code='budget';
	public $table_alias='bu';

	function defineFields(){
		parent::defineFields();

		$this->newField('name')->sortable(true);
		$this->newField('deadline')->datatype('date')->sortable(true);
		$this->newField('accepted')->datatype('boolean')->sortable(true);
		$this->newField('closed')->datatype('boolean')->sortable(true);
		$this->newField('amount_eur')->datatype('money')->sortable(true);
        $this->newField('amount_spent')->datatype('money')->calculated(true)->sortable(true);


		$this->newField('success_criteria')
			->datatype('list')
			->listData(array(
                0=>'Unconfirmed',
				1=>'Requirements completed',
				2=>'Mandays worked',
				3=>'Budget depleted',
				4=>'Deadline Reached',
			))
            ;
		$this->newField('mandays')
			->datatype('int')
            ;
		$this->newField('cur_mandays')
			->datatype('int')
			->calculated(true)
            ;
//                $this->newField('cur_mandays_developer')
//			->datatype('int')
//			->calculated(true)
//            ;
    $this->newField('days_spent')
			->datatype('int')
			->calculated(true)
            ;
    $this->newField('days_spent_lastweek')
			->datatype('int')
            ->caption('Days Spent Last Week')
			->calculated(true)
            ;

		$this->newField('project_id')
            ->sortable(true)
			->refModel('Model_Project')
            ;
        $this->addField('client')
            ->sortable(true)
            ->calculated(true);

        $this->addField('team')
            ->sortable(true)
            ->calculated(true);

//        $u=$this->api->getUser();
//        if($u->isInstanceLoaded() && $u->get('is_client')){
//            $this->addCondition('client_id',$u->get('client_id'));
//        }
	}
    function calculate_client(){
        return $this->add('Model_Client')
            ->dsql()
            ->join('project pr','cl.id=pr.client_id')
            ->field('cl.name')
            ->limit(1)
            ->where('pr.id=bu.project_id')
            ->select();
    }
    function calculate_team(){
        return $this->add('Model_Payment')
            ->dsql()
            ->where('pa.budget_id=bu.id')
            ->field('count(*)')
            ->select();

    }
	function scopeFilter($dsql){
		if($sc=$this->api->recall('scope')){
			if($sc['client'])$dsql->where('client_id',$sc['client']);
		}
	}
	function calculate_cur_mandays(){
		return $this->add('Model_Report')
			->dsql()
			->field('round(sum(R.amount/60/8),1)')
			->where('R.budget_id=bu.id')
			->select();
	}
    function calculate_amount_spent(){
		return $this->add('Model_Timesheet')
			->dsql()
            ->join('payment pa','pa.user_id=T.user_id')
			->field('sum(T.minutes)/60*pa.hourly_rate')
			->where('T.budget_id=bu.id')
            ->where('pa.budget_id=bu.id')
			->select();
    }
        function calculate_days_spent(){
		return $this->add('Model_Timesheet')
			->dsql()
			->field('sum(T.minutes)/60/8')
			->where('T.budget_id=bu.id')
			->select();
	}
        function calculate_days_spent_lastweek(){
		return $this->add('Model_Timesheet')
			->dsql()
			->field('sum(T.minutes)/60/8')
			->where('T.budget_id=bu.id')
                        ->where(" YEARWEEK(date) = YEARWEEK(CURRENT_DATE - INTERVAL 7 DAY) ")
			->select();
	}
         
}
