<?
class Model_Budget extends Model_Table {
	public $entity_code='budget';
	public $table_alias='bu';

	function defineFields(){
		parent::defineFields();

		$this->newField('name')->sortable(true);

        $this->addField('priority')->type('list')->listData(array(
                    'Select...',
                    '1-low'=>'1 - Work on project in free time',
                    '2-schedule'=>'2 - Work according to schedule',
                    '3-normal'=>'3 - Work to complete at earliest',
                    '4-urgent'=>'4 - Urgent, work fast, call if blockers',
                    '5-overtime'=>'5 - Critical, working over-time, possible penalties',
                    '6-nosleep'=>'6 - Not going to sleep until finished'
                    ))
                    ;
                        

        $this->addField('state')->type('list')->listData(array(
                    'Select...',
                    '0-irrelevant'=>'State is not applicable',
                    '1-lead'=>'1 - Expressed interest in project',
                    '2-discuss'=>'2 - Had brief discussion about project',
                    '3-quote'=>'3 - Expects quotation',
                    '4-quotereview'=>'4 - Reviews quotation',
                    '5-mutual'=>'5 - Mutually agreed on quote, deposit payable',
                    '6-deposited'=>'6 - Initial deposit sent',
                    '7-gotdeposit'=>'7 - Deposit received',
                    '8-papers'=>'8 - Contract signed, timeline and spec agreed',
                    '9-devel'=>'9 - Development started',
                    '10-qa'=>'10 - Internal Q/A',
                    '11-clientqa'=>'11 - Client Q/A',
                    '12-bugfixes'=>'12 - Bugfixes received, fixing',
                    '13-acceptance'=>'13 - Bugs fixed, waiting for acceptance',
                    '14-deployment'=>'14 - Waiting for installation instrucitons',
                    '15-launced'=>'15 - Deployed. 30-day warranty',
                    '16-support'=>'16 - On-going support',
                    ))
                    ;

        /*
		$this->newField('start_date')->datatype('date')->sortable(true)
            ->defaultValue(date('Y-m-d',strtotime('next monday')));
            */

		$this->newField('deadline')->datatype('date')->sortable(true);

		$this->newField('accepted')->datatype('boolean')->sortable(true);

		$this->newField('closed')->datatype('boolean')->sortable(true);

        $this->addfield('is_moreinfo_needed')->type('boolean')
            ->caption('Waiting for more information from client');

        $this->addField('is_delaying')->type('boolean')
            ->caption('Project is behind schedule');

        $this->addField('is_overtime')->type('boolean')
            ->caption('Project was underquoted');





		$this->newField('amount')->datatype('money')->sortable(true);

		$this->newField('expenses')->datatype('money')->sortable(true);
		$this->newField('expenses_descr')->datatype('text');


        $this->newField('amount_spent')->datatype('money')->calculated(true)->sortable(true);

        $this->addField('currency')->type('list')->listData(array(
                    'Select...',
                    'EUR'=>'Euros',
                    'GBP'=>'UK Pounds',
                    'USD'=>'US Dollars'
                    ))
            ;


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
        $this->scopeFilter();
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
	function scopeFilter(){
		if($sc=$this->api->recall('scope')){
			if($sc['budget'])$this->addCondition('id',$sc['budget']);
		}
	}
	function calculate_cur_mandays(){
		return $this->add('Model_Timesheet')
			->dsql()
			->field('round(sum(T.minutes/60/8),1)')
			->where('T.budget_id=bu.id')
			->select();
	}
    function calculate_amount_spent(){
		return $this->add('Model_Timesheet')
			->dsql()
            ->join('payment pa','pa.user_id=T.user_id')
			->field('sum(T.minutes/60*pa.hourly_rate)')
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
