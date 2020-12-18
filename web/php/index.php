<?php
if(isset($_GET['ambientC']) && isset($_GET['objectC'])){
	$ambientC = $_GET['ambientC'];
	$objectC = $_GET['objectC'];
	$b = array(
		'ambientC'=>$ambientC,
		'objectC'=>$objectC
	);
	$filedata = fopen("data.json", "w");
	if( $filedata == false )
	{
		echo "error make file ";
		exit();
	}
	$data = json_encode($b);
	fwrite($filedata, $data );
	fclose($filedata);
	echo($data);
}
?>

<!DOCTYPE html>

<html>
<head>
  <title>Biểu đồ nhiệt độ và khoảng cách</title>
</head>
<head>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    	<script src="https://code.highcharts.com/highcharts.js"></script>
    	<script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>

<body>

<div style="height:410px;min-height:100px;margin:0 auto;" id="main"> </div>  

<script type="text/javascript">

Highcharts.setOptions({
global: {
useUTC: false
}
});
function activeLastPointToolip(chart) {
var points = chart.series[0].points;
chart.tooltip.refresh(points[points.length -1]);
}
var temp_1 = 10.21;
// alert(json_temp);
$('#main').highcharts({
	chart: {
		type: 'spline',
		animation: Highcharts.svg,
		marginRight: 10,
	events: {
		load: function () {

			var seriesC = this.series[0],
			seriesD = this.series[1],
			chart = this;
			setInterval(function () {
				var xmlhttp = new XMLHttpRequest();
				var url = "data.json";
				xmlhttp .overrideMimeType("application/json");
				xmlhttp.onreadystatechange = function() {
				    if (this.readyState == 4 && this.status == 200) {
				        var myArr = JSON.parse(this.responseText);
			    			json_ambientC = myArr['ambientC']
     		  		    		json_objectC = myArr['objectC']
			       			console.log("ambientC:", json_ambientC );
   						console.log("objectC:", json_objectC );
				    }
			};
			xmlhttp.open("GET", url, true);
			xmlhttp.send();
			
			var x = (new Date()).getTime(), 
			y_ambientC = Number(json_ambientC),
			y_objectC = Number(json_objectC);
					
			seriesC.addPoint([x, y_ambientC], true, true);		
			seriesD.addPoint([x, y_objectC], true, true);
			activeLastPointToolip(chart);
			}, 1000);
		}
	}
},
title: {
	text: 'Biểu đồ đo nhiệt độ theo thời gian'
},
credits: { 
	enabled: false 
},
xAxis: {
	type: 'datetime',
	tickPixelInterval: 150,
	title: {
		text: 'time'
	},
},
yAxis: {
	title: {
		text: 'data sensor'
	},
	plotLines: [{
		value: 0,
		width: 1,
		color: '#808080'
	}]
},
tooltip: {
	formatter: function () {
		return '<b>' + this.series.name + '</b><br/>' +
Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
Highcharts.numberFormat(this.y, 2);
}
},
legend: {
enabled: false
},
exporting: {
enabled: false
},
series: [
{
name: 'Nhiệt độ cảm biến',
data: (function () {
// generate an array of random data
var data = [],
time = (new Date()).getTime(),
i;
for (i = -19; i <= 0; i += 1) {
	data.push({
		x: time + i * 1000,
		y: Math.random()
	});
}
return data;
}())
},
{
name: 'Nhiệt độ nước đá',
data: (function () {
// generate an array of random data
var data = [],
time = (new Date()).getTime(),
i;
for (i = -19; i <= 0; i += 1) {
	data.push({
		x: time + i * 1000,
		y: Math.random()
	});
}
return data;
}())
}]
}, function(c) {
activeLastPointToolip(c)
});
		
	</script>

</body>



</html> 
