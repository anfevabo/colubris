<?php
class Chart extends View {
    public $options=array('chart'=>array('defaultSeriesType'=>'column'),'series'=>array());
    function init(){
        $this->js()
            ->_load('highcharts/highcharts')
            ->_load('highcharts_helper')
            ;
        parent::init();
    }
    function set($key,$value){
        $this->options[$key]=$value;
        return $this;
    }
    function setDefaultType($t){
        $this->options['chart']['defaultSeriesType']=$t;
        return $this;
    }
    function setHeight($t){
        $this->options['chart']['height']=$t;
        return $this;
    }
    function series($series){
        $this->options['series'][]=$series;
        return $this;
    }
    function render(){
        $this->js(true)->univ()->highchart($this->options);
        parent::render();
    }
    function defaultTemplate(){
        return array('view/chart');
    }
}
