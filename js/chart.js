google.charts.load('current', {'packages':['line']});
google.charts.setOnLoadCallback(function() {
$(function() {
	//initialize
	$('.loading').show();
	callAjax();
	refreshChart();

	$("#cookid").change(function() {
		$('#chart_div').html('');
		$('#toggleLines').hide();
		$('#selectCook').hide();
		$('.loading').show();
		callAjax();
		refreshChart();
		if ($("#cookid option:selected").text().indexOf("Active")>=0) {
			clearInterval(chartInterval);
			chartInterval=setInterval(refreshChart,10000);

			clearInterval(ajaxInterval);
			ajaxInterval=setInterval(callAjax,10000);
		} else {
			clearInterval(chartInterval);
			clearInterval(ajaxInterval);
		}
	});

	$("#showFood").change(function() {
		refreshChart();
	});

	$("#showPit").change(function() {
		refreshChart();
	});

	function refreshChart() {
		$.ajax({
			url: "getdata.php",
			data: {'reqType': 'chart', 'cookid': $("#cookid").val()},
			type: "POST",
			async: true,
			dataType: "json",
			success:function(data) {
				drawChart(data);
			}
		});
	}

	function callAjax() {
		$.ajax({
			url: "getdata.php",
			type: "POST",
			data: {'reqType': 'temps', 'cookid': $("#cookid").val()},
			async: false,
			dataType: "json",
			success:function(data){
				/*if (data['when'].indexOf("Cook #")>=0) {
					$("#food").html("0");
					$("#pit").html("0");
				} else {*/
					$("#food").html(data['probe1']);
					$("#pit").html(data['probe2']);
					//}
				$("#when").html(data['when']);
			}
		});

	}

	//if active cook is being displayed, set callback to refresh chart every 10 seconds
	if ($("#cookid option:selected").text().indexOf("Active")>=0) {
		chartInterval=setInterval(refreshChart,10000);
		ajaxInterval=setInterval(callAjax,10000);
	}
});//jquery load
}); //google chart

var data, chart;
function drawChart(chartJson) {
if ($("#showPit").is(":checked") || $("#showFood").is(":checked")) {
	var options = {
		hAxis: {
			title: 'Time',
			textStyle: {
				fontSize: 18
			},
			titleTextStyle: {
				fontSize: 20
			}
		},
		vAxis: {
			title: 'Temp (F)',
			textStyle: {
				fontSize: 18
			},
			titleTextStyle: {
				fontSize: 20
			}
		},
		colors: ['#5b145b', '#008789'],
		chartArea: {
			left: 0,
			top: 10,  
			width: "100%",
		},
		explorer: {
			actions: ['dragToZoom', 'rightClickToReset'],
			axis: 'horizontal',
			keepInBounds: true,
			maxZoomIn: 10.0
		}
	};

	// Create our data table out of JSON data loaded from server.
	data=new google.visualization.DataTable(chartJson);

	// Instantiate and draw our chart, passing in some options.
	chart=new google.charts.Line(document.getElementById('chart_div'));
	chart.draw(data, google.charts.Line.convertOptions(options));

	/*add event for body clicks that clears the tooltip
	document.body.addEventListener('click',clearSelection,true);

	/add event for tootltip delete data point click to perform the query
	chart.setAction({
		id: 'delPoint',
		text: 'X - delete data point',
		action: function() {
			selection=chart.getSelection();
			dtstring=data.getFormattedValue(selection[0].row,0);
			$.ajax({
				url: 'delpoint.php',
				type:'POST',
				data: {'cookid': $("#cookid").val(), 'time': dtstring},
				success:function(data){
					if (data!='fail') {
						$('#chart_div').html('');
						$('#toggleLines').hide();
						$('#selectCook').hide();
						$('.loading').show();
						$.ajax({
							url: "getdata.php",
							data: {'reqType': 'chart', 'cookid': $("#cookid").val()},
							type: "POST",
							dataType: "json",
							async: true,
							success:function(data) {
								drawChart(data);
							}
						});
					} else { alert('failed');}
				}
			});
		}
		});*/

	//show or hide the food or pit graphs based on user input
	if (!$("#showPit").is(":checked") || !$("#showFood").is(":checked")) {
		view=new google.visualization.DataView(data);
		if (!$("#showFood").is(":checked")) {
			view.hideColumns([1]);
		}
		if (!$("#showPit").is(":checked")) {
			view.hideColumns([2]);
		}
		chart.draw(data, google.charts.Line.convertOptions(options));
	}

	//chart loaded, hide loading gif, show UI
	$('.loading').hide();
	$('#toggleLines').show();
	$('#selectCook').show();
} else {
	$("#chart_div").html("");
}
} //drawChart

/*clears tooltip when anything outside of chart is clicked/tapped
function clearSelection (e) {
if (!document.querySelector('#chart_div').contains(e.srcElement)) {
	chart.setSelection();
}
}

function addEvent(element, evnt, funct){
if (element.attachEvent) {
	return element.attachEvent('on'+evnt, funct);
} else {
	return element.addEventListener(evnt, funct, false);
}
}*/