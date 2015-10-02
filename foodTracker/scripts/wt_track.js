var chart;
$(function () {
	$.post('chartData.php',{"type":"wieghtlbsdelta"},function(mydata) {
		var array_data = []
		$.each(mydata.dataPoints, function(i,v){
       		$.each(v,function(index,val){
       			array_data.push([val.time, parseFloat(val.weight)]);                   		
       	});
       		
       	});
    $('#container').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Weight Change'
        },
        xAxis: {
        	type: 'datetime',
        		 title: {
                     text: 'Date Time'
                 }
        },
        yAxis: {
            title: {
                text: 'LBs'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Weight',
            data: array_data
        }]
    });
	},"json");
	$.post('chartData.php',{"type":"wieghtlcPercent"},function(mydata) {
		var array_data = []
		
		$.each(mydata.dataPoints, function(i,v){
			var percentPoints =[];
       		$.each(v.percentPoints,function(index,val){
       			percentPoints.push([val.time, parseFloat(val.weight)]);                   		
       		});
       		array_data.push({
                "name": v.name,
                
                "data": percentPoints
            });
       	});
    $('#container2').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Weight %'
        },
        xAxis: {
        	type: 'datetime',
        		 title: {
                     text: 'Date Time'
                 }
        },
        yAxis: {
            title: {
                text: 'Percentage Lost'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: array_data
    });
	},"json");
	
	$.post('chartData.php',{"type":"wieghtlcPBar"},function(mydata) {
		var array_data = [];
		var names_arr = [];
		$.each(mydata.dataPoints, function(i,v){
			array_data.push(parseFloat(v.percentPoints));
			names_arr.push(v.name);
       	});
		chart = new Highcharts.Chart({
	            chart: {
	        	
	        	renderTo: 'container3',
	            type: 'column',
	            margin: 75,
	            options3d: {
	                enabled: true,
	                alpha: 15,
	                beta: 15,
	                depth: 50,
	                viewDistance: 25
	            }
	        },
	        title: {
	            text: 'Total Weight Change %'
	        },
	        subtitle: {
	            text: 'Higher is better and means you lost more weight overall'
	        },
	        plotOptions: {
	            column: {
	                depth: 25
	            }
	        },
	        xAxis: {
	            categories: names_arr
	        },
	        yAxis: {
	            opposite: true
	        },
	        series: [{
	            name: '% of weight loss',
	            data: array_data
	        }]
	    });

    function showValues() {
        $('#R0-value').html(chart.options.chart.options3d.alpha);
        $('#R1-value').html(chart.options.chart.options3d.beta);
    }

    // Activate the sliders
    $('#R0').on('change', function () {
        chart.options.chart.options3d.alpha = this.value;
        showValues();
        chart.redraw(false);
    });
    $('#R1').on('change', function () {
        chart.options.chart.options3d.beta = this.value;
        showValues();
        chart.redraw(false);
    });

    showValues();
	},"json");
	
});