<?php
class page_manager_statistics extends Page {
    function init(){
        parent::init();

        for($x=0;$x<10;$x++){
            $this->add('DeveloperChart')
                ->draw($x);
        }
    }


}
class DeveloperChart extends Chart {
    function draw($week_offset=0){
        $ch=$this;

        //array_walk($data,function(&$row){$row=array((int)$row;});
        $ch->setDefaultType('line');
        $ch->setHeight('200');
        $ch->set('xAxis',array('type'=>'datetime','dateTimeLabelFormats'=>array('day'=>'%e','hour'=>'%e (%H)') ));
        $ch->set('yAxis',array('max'=>100, 'min'=>0,'title'=>null,'labels'=>array()));

        $m=$this->add('Model_Developer_Stats');
        $m->setDateRange(date('Y-m-d',strtotime('last monday')),date('Y-m-d'));
        $data=$m->getRows(array('id','name','hours_today'));
        $result=array();
        foreach($data as $row){
            $row['name']=preg_replace('/ .*/','',$row['name']);
            $result[$row['name']]=$row['hours_today']+0;
        }

        $d=$this->add('Model_Developer');
        $developers=$d->getRows();

		if(date('Y-m-d',strtotime('-'.$week_offset.' weeks'))==date('Y-m-d',strtotime('monday',strtotime('-'.$week_offset.
							' weeks')))){
			$min=strtotime(date('Y-m-d',strtotime('-'.$week_offset.' weeks')));
		}else{
			$min=strtotime('last monday',strtotime('-'.$week_offset.' weeks'));
		}
        $max=strtotime('+20 hours',strtotime('-2 days',strtotime('sunday',strtotime('-'.$week_offset.' weeks'))));
        $sun=strtotime('+20 hours',strtotime('sunday',strtotime('-'.$week_offset.' weeks')));

        $ch->set('title',array('text'=>'Week '.date('d/m/Y',$min).' to '.date('d/m/Y',$max)));

        $min*=1000;$max*=1000;$sun*=1000;



        foreach($developers as $devel){
            $d->loadData($devel['id']);
            $data=$d->getTimesheets()->dsql()
                ->field('unix_timestamp(date) d,minutes/60 h,concat(title," (",minutes," m)") title')
                ->order('date')
                ->where('week(date)-if(weekday(date)=6,1,0)=week(now())-'.(int)$week_offset)
                ->do_getAssoc();


            $result=array(array($min,100));$target=$d->get('weekly_target');


            foreach($data as $key=>$row){
                $target-=$row['h'];
                $result[]=array(
                        'x'=>(int)$key*1000, // milis
                        'y'=>max(round($target/$d->get('weekly_target')*100),-50),
                        'name'=>$row['title']
                        );
            }

            $data=$result;


            $ch->series(array('name'=>$devel['name'],'data'=>$data));

        }


        $ch->series(array('name'=>'Baseline','data'=>array(
                        array('x'=>$min,'y'=>100,'name'=>'Start of Week'),
                        array('x'=>$max,'y'=>0,'name'=>'Friday Evening'),
                        array('x'=>$sun,'y'=>0,'name'=>'Sunday')
                        )));

    }
}
