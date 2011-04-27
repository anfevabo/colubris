$.each({
highchart: function(options){
    var x=this.jquery.attr('id');
    options['chart']['renderTo']=x;
    console.log('highchart options',options);
    new Highcharts.Chart(options);
}
},$.univ._import);
