<?

class Model_Timesheet extends Model_Table {

    public $entity_code = 'timesheet';
    public $table_alias = 'T';

    function defineFields() {
        parent::defineFields();
        $this->newField('title');
        $this->newField('user_id')->refModel('Model_User');
//        $this->newField('report_id')->refModel('Model_Report');
        $this->newField('date')->datatype('datetime')->defaultValue(date('Y-m-d'));
        $this->newField('start');
        $this->newField('end');
        $this->newField('minutes')->datatype('int');
        $this->addField('budget_id')->refModel('Model_Budget');
        $this->addField('is_closed')->calculated(true);;
        $this->addField('amount_spent')->caption("Amount Spent")
                ->datatype('int')
                ->calculated(true);
        if ($this->api->page != 'minco') {
            $u = $this->api->getUser();
            if (!$u->get('is_admin') && !$u->get('is_client')) {
                $this->setMasterField('user_id', $this->api->getUserID());
            }
        }

        if($sc=$this->api->recall('scope')){
            //if($sc['budget'])$this->addCondition('budget_id',$sc['budget']);
        }

//        $this->newField('hours_spent')
//                ->datatype('int')
//                ->calculated(true);
    }
    function calculate_is_closed(){
           $q = $this->add('Model_Budget')->dsql();
        $q->where('id=T.budget_id');
        $q->field('closed');
        return $q->select();
    }

     function calculate_amount_spent(){
           $q = $this->add('Model_Payment')->dsql();
        $q->where('user_id=T.user_id');
        $q->where('budget_id=T.budget_id');
        $q->field('round(T.minutes * hourly_rate /60, 2)');
        return $q->select();
    }
    function total_amount_spent(){
//        $model=$this->add('Model_Budget');
//        $model->addCondition()
    }
    function getHoursToday($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/60)');
        $dql->where('user_id=' . $userid);
        $dql->where('date>now() - interval 1 day');
        $result = $dql->do_getOne();
        return $result;
    }

    function getHoursLastday($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/60)');
        $dql->where('user_id=' . $userid);
        $dql->where('date<now() - interval 1 day');
        $dql->where('date>now() - interval 2 day');
        $result = $dql->do_getOne();
        return $result;
    }

    function getHoursWeekly($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/(60))');
        $dql->where('user_id=' . $userid);
        $dql->where('date>now() - interval 1 week');
        $result = $dql->do_getOne();
        return $result;
    }

    function getHoursLastWeek($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/(60))');
        $dql->where('user_id=' . $userid);
        $dql->where('date<now() - interval 1 week');
        $dql->where('date>now() - interval 2 week');
        $result = $dql->do_getOne();
        return $result;
    }

    function getHoursMonthly($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/(60))');
        $dql->where('user_id=' . $userid);
        $dql->where('date>now() - interval 1 month');
        $result = $dql->do_getOne();
        return $result;
    }

    function getHoursLastMonth($userid) {
        $dql = $this->dsql();
        $dql->field('round(sum(minutes)/(60))');
        $dql->where('user_id=' . $userid);
        $dql->where('date<now() - interval 1 month');
        $dql->where('date>now() - interval 2 month');
        $result = $dql->do_getOne();
        return $result;
    }

    function status($hours, $target, $type='weekly') {
        $day = date('w');

        if ($type == 'weekly') {
            $act_target = ($target / 5) * $day;
            if ($act_target > $hours) {
                return 'BEHIND SCHEDULE';
            } else {
                return 'ON TIME';
            }
        }
        if ($type == 'monthly') {
            $working_days = $this->calculateWorkingDaysInMonth();

            if ($target) {
                $act_target = $hours / ($target / 5);
                if ($act_target < $working_days) {
                    return 'BEHIND SCHEDULE';
                } else {
                    return 'ON TIME';
                }
            }
        }
        return 'ON TIME';
    }

    function calculateWorkingDaysInMonth($year = '', $month = '') {
        if ($year == '') {
            $year = date('Y');
        }
        if ($month == '') {
            $month = date('m');
        }

        $startdate = strtotime($year . '-' . $month . '-01');

        $enddate = time();
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
