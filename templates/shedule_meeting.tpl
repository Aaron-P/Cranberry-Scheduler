{extends file="template.tpl"}
{block name="page_title"}Schedule Meeting{/block}

{block name="page_head"}
	<link type="text/css" rel="stylesheet" href="css/base_style.css">
	<link type="text/css" rel="stylesheet" href="css/schedule_meeting.css">
	<link rel='stylesheet' type='text/css' href='css/fullcalendar.css' />
	<link rel='stylesheet' type='text/css' href='css/fullcalendar.print.css' media='print' />
	<link type="text/css" href="css/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="js/jquery-1.6.4.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="js/resize_fix.js"></script>
	<script type="text/javascript" src="js/jquery.dateFormat-1.0.js"></script>
	<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
	<script type='text/javascript' src='js/fullcalendar.js'></script>
	<script type='text/javascript'>
		$(document).ready(function() {
		
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			
			var calendar = $('#calendar').fullCalendar({
				defaultView: 'agendaDay',
				allDaySlot: false,
				minTime: '6:00',
				maxTime: '22:00',

				header: {
					left: 'prev',
					center: 'today',
					right: 'next'/*month,basicWeek,basicDay*/
				},
/*					dayClick: function(date, allDay, jsEvent, view) {
					var timestamp = Math.round((date).getTime() / 1000);
					document.location.href = "shedule_meeting.htm?time=" + timestamp;
				},
*/
				aspectRatio : 0,


				selectable: true,
				selectHelper: true,
				unselectAuto: false,
				select: function(start, end, allDay, jsEvent) {
					if (jsEvent != undefined)
						changeTimes(Math.round((start).getTime()/1000), Math.round((end).getTime()/1000));
//							window.location.href = "http://localhost/Cranberry-Scheduler/index.php?page=shedule_meeting&start="+Math.round((start).getTime()/1000)+"&end="+Math.round((end).getTime()/1000);
				},
				editable: true,
				events: [
					{
						title: 'Interview',
						start: new Date(2011, 11, 9, 13, 15),
						end: new Date(2011, 11, 9, 14, 30),
						url: 'meeting_overview___.htm',
						allDay: false
					}
/*						{
						title: 'All Day Event',
						start: new Date(y, m, 1)
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d-5),
						end: new Date(y, m, d-2)
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d-3, 16, 0),
						allDay: false
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d+4, 16, 0),
						allDay: false
					},
					{
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d+1, 19, 0),
						end: new Date(y, m, d+1, 22, 30),
						allDay: false
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/'
					}*/
				]
			});
			
		});
	</script>
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
<h1>Schedule Meeting</h1><br />
<hr /><br />

<div id="form_background">
<div id="form_left">

<form method="POST" action="main_.htm">


<label class="label">Location<br />
<span class="small">&nbsp;</span>
</label>
<select class="input">
<option selected="selected">-- Select Location --</option>
<option>EB 3048 - HCI Lab</option>
<option>EB 2029 - Senior Project Lab</option>
</select><br />

<label class="label">Date<br />
<span class="small">&nbsp;</span>
</label>
<input class="input" type="text" name="date" id="date_picker" onchange="checkSelect();" /><br />

<label class="label">Start Time<br />
<span class="small">&nbsp;</span>
</label>
<input class="input" type="text" name="start" id="start_time" value="00:00" onchange="checkSelect();" /><br />

<label class="label">Finish Time<br />
<span class="small">&nbsp;</span>
</label>
<input class="input" type="text" name="finish" id="finish_time" value="00:00" onchange="checkSelect();" /><br />

<label class="label">Meeting Type<br />
<span class="small">&nbsp;</span>
</label>
<input onclick="checkMeeting()" id="interview" type="radio" name="group1" value="Milk" checked="1">&nbsp;<label>Interview</label>
<input onclick="checkMeeting()" id="rehearsal" type="radio" name="group1" value="Butter">&nbsp;<label>Rehearsal</label><br />

<div id="volunteer_info">
<label id="num_volunteers_label" class="label"># Volunteers<br />
<span class="small">&nbsp;</span>
</label>
<input class="input" type="text" name="finish" id="num_volunteers" value="" /><br />

<!--
<div style="text-align: center;">
<label class="">Meeting will be recorded<span class="small">&nbsp;</span></label>
<input type="checkbox" name="group2" value="Milk" checked="1">
</div><br />-->



</div>

<label class="label">Description<br />
<span class="small">&nbsp;</span>
</label>
<textarea id="description" class="input"></textarea><br /><br />

<center><input type="submit" value="Submit" id="submit" />&nbsp;|&nbsp;<input type="button" value="Cancel" id="submit" /></center>
<div class="spacer"></div>

</form>
</div>

<div id="calendar"></div>

</div>

</div>

<script type="text/javascript">
	function  checkSelect()
	{
		var date = $("#date_picker").val();
		var start = $("#start_time").val();
		var end = $("#finish_time").val();

		var startTime = Math.round((new Date(date+" "+start)).getTime() / 1000);
		var endTime = Math.round((new Date(date+" "+end)).getTime() / 1000);

		if (date != "" && start != "" && end != "")
		{
			$('#calendar').fullCalendar('select', new Date(date+" "+start), new Date(date+" "+end), false);
		}
	}



function toTimestamp(year,month,day,hour,minute,second){
 var datum = new Date(Date.UTC(year,month-1,day,hour,minute,second));
 return datum.getTime()/1000;
}


	$(function() {
		$( "#date_picker" ).datepicker({
/*			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true*/
		});
		$('#start_time').timepicker({
			ampm: true,
			timeFormat: 'h:mm TT'
		});
		$('#finish_time').timepicker({
			ampm: true,
			timeFormat: 'h:mm TT'
		});
	});

	function checkMeeting()
	{
/*		if ($("#interview").is(':checked'))
			$("#volunteer_info").show();
		else
			$("#volunteer_info").hide();*/

		if ($("#interview").is(':checked'))
		{
			$("#num_volunteers").prop('disabled', false);
			$("#num_volunteers").removeClass('inputDisabled');
			$("#num_volunteers_label").removeClass('textDisabled');
		}
		else
		{
			$("#num_volunteers").prop('disabled', true);
			$("#num_volunteers").addClass('inputDisabled');
			$("#num_volunteers_label").addClass('textDisabled');
		}
	}
	checkMeeting();


	function getUrlVars()
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	function formatTime(timestamp)
	{
		var time = new Date(timestamp);
		var hours = time.getHours();
		var minutes = time.getMinutes();

		var suffix = "AM";
		if (hours > 11)
			suffix = "PM";
		if (hours > 12)
			hours -= 12;
		if (hours == 0)
			hours = 12;

		if (minutes < 10)
			minutes = "0" + minutes;

		return hours+":"+minutes+" "+suffix;		
	}


	function changeTimes(start, end)
	{
		var start = start;
		var end = end;
		var date = new Date(start * 1000);

		$("#date_picker").val($.format.date(date.toString(), "MM/dd/yyyy"));
		$("#start_time").val(formatTime(start * 1000));
		$("#finish_time").val(formatTime(end * 1000));
	}

/*
	if (getUrlVars()["start"] != undefined && getUrlVars()["end"] != undefined)
	{
		var start = getUrlVars()["start"];
		var end = getUrlVars()["end"];
		var date = new Date(start * 1000);

		$("#date_picker").val($.format.date(date.toString(), "MM/dd/yyyy"));
		$("#start_time").val(formatTime(start * 1000));
		$("#finish_time").val(formatTime(end * 1000));
	}*/

</script>


				<div class="spacer_content"></div>
{/block}
