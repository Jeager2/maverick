var noSleep = new NoSleep();
var allowedToSleep=true;

$(function(){

	$("#silenceAlert").click(function(){
		$("#silenceAlert").prop("disabled",true);
		audio.pause();
		silenceAlerts=true;

		var counter=300;
		var countdown = function(){
			counter--;
			$("#silenceAlert").prop('value', '('+counter+')');
			if (counter==0) {
				//$("#silenceAlertDiv").css("display","none");
				$("#silenceAlertDiv").hide();
				$("#alertType").html('');
				$("#silenceAlert").prop('value', 'Silence');
				$("#silenceAlert").prop("disabled",false);
				clearInterval(handle);
				handle=0;
				silenceAlerts=false;
			}
		}
		var handle=setInterval(countdown,1000);
	});
	
	$('#toggleCook').click(function(){
		$.ajax({
			url: 'togglecook.php',
			type: 'POST',
			data: $("#alertsForm").serialize(),
			success: function(data) {
				if (data=='Start New Cook') {
					$('#toggleCook').prop('value',data);
					$('#toggleCook').removeClass().addClass("btn btn-block btn-success");
					$("*", "#alertsForm").prop('disabled', false);
					$('#alertsDiv').show();
					noSleep.disable();
					allowedToSleep=true;
				} else {
					$('#toggleCook').prop('value',data);
					$('#toggleCook').removeClass().addClass("btn btn-block btn-danger");
					//$('#alertsDiv').css("display","none");
					$('#alertsDiv').hide();
					if (allowedToSleep) {
						noSleep.enable();
						allowedToSleep=false;
					}
				}
			},
		});
		$('#silenceAlertDiv').hide();
		$('#alertType').html("");
	});

	var callAjax = function(){
		$.ajax({
			url:'interval.php',
			type:'POST',
			success:function(data){
				if(data=='Start New Cook') {
					$('#live').html('');
					$('#toggleCook').prop('value',data);
					$('#toggleCook').removeClass().addClass("btn btn-block btn-success");
					noSleep.disable();
					$('#alertsDiv').show();
				} else {
					$('#live').html('LIVE');
					$('#toggleCook').prop('value', data);
					$('#toggleCook').removeClass().addClass("btn btn-block btn-danger");
					$('#alertsDiv').hide();
				}
			}
		});

	}
	setInterval(callAjax,1000);

	var checkAlerts = function(){
		noSleep.disable();
		allowedToSleep=true;
		$.ajax({
			url:'togglecook.php',
			type:'POST',
			data: 'p1=alerts',
			success:function(data){
				if(data!='' && silenceAlerts==false) {
					audio.play();
					//$("#silenceAlertDiv").css("display","block");
					$("#silenceAlertDiv").show();
					$("#alertType").html(data);
				} else {
					audio.pause();
					if (silenceAlerts==false) {
						//$("#silenceAlertDiv").css("display","none");
						$("#silenceAlertDiv").hide();
						$("#alertType").html("");
					}
				}
			}
		});
	}
	setInterval(checkAlerts,5000);
});