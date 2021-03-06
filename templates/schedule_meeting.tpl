{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Schedule Meeting{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/fullcalendar.css" />
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/fullcalendar.print.css" media="print" />
<link type="text/css" rel="stylesheet" href="{$baseUrl}css/base_style.css">
<link type="text/css" rel="stylesheet" href="{$baseUrl}css/schedule_meeting.css">
<link type="text/css" href="{$baseUrl}css/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="{$baseUrl}js/jquery-1.6.4.js"></script>
<script type="text/javascript" src="{$baseUrl}js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="{$baseUrl}js/resize_fix.js"></script>
<script type="text/javascript" src="{$baseUrl}js/jquery.dateFormat-1.0.js"></script>
<script type="text/javascript" src="{$baseUrl}js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="{$baseUrl}js/fullcalendar.js"></script>
<script type="text/javascript">
	var currentLocation = "";

	function enableInput(element)
	{
		element.prop("disabled", false);
		element.removeClass("inputDisabled");
		element.removeClass("textDisabled");
	}
	function disableInput(element)
	{
		element.prop("disabled", true);
		element.addClass("inputDisabled");
		element.addClass("textDisabled");
	}

	function formEnable(enable)
	{
		if (enable)
		{
			enableInput($("#date_picker"));
			enableInput($("#start_time"));
			enableInput($("#finish_time"));
			enableInput($("#interview"));
			enableInput($("#rehearsal"));
			if ($("#interview").is(":checked"))
				enableInput($("#num_volunteers"));
			enableInput($("#description"));
			enableInput($("#submit"));
		}
		else
		{
			disableInput($("#date_picker"));
			disableInput($("#start_time"));
			disableInput($("#finish_time"));
			disableInput($("#interview"));
			disableInput($("#rehearsal"));
			disableInput($("#num_volunteers"));
			disableInput($("#description"));
			disableInput($("#submit"));
		}
	}

	function changeLocation()
	{
		if ($("#location"))
			currentLocation = $("#location").val();
		else
			currentLocation = "";

		if (currentLocation != "")
			formEnable(true);
		else
			formEnable(false);

		$("#calendar").empty();
		doCal();
	}


	function validateForm()
	{
		return true;
	}
/*
		if ($("#location").val() == "")
		{
			setError($("#location"));
		}

			$("#date_picker").val())
			$("#start_time")
			$("#finish_time")
			$("#interview")
			$("#rehearsal")
			$("#num_volunteers")
			$("#description")

		.is(":checked")
		.val()
*/


	function doCal()
	{
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		var calendar = $("#calendar").fullCalendar({
			defaultView: "agendaDay",
			allDaySlot: false,
			minTime: "6:00",
			maxTime: "22:00",

			header: {
				left: "prev",
				center: "today",
				right: "next"/*month,basicWeek,basicDay*/
			},

/*			dayClick: function(date, allDay, jsEvent, view) {
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
//				window.location.href = "http://localhost/Cranberry-Scheduler/index.php?page=shedule_meeting&start="+Math.round((start).getTime()/1000)+"&end="+Math.round((end).getTime()/1000);
			},

			editable: false,
			eventSources: [
				{
					url: "{$baseUrl}feed.php",
					color: "red",
					type: "POST",
		            data: {
		                location: currentLocation
		            }
				}
			]
		});
	}

	$(document).ready(function() {
		doCal();
{if $inputFields}
		$("#location").val("{$inputFields.LocationID}");
		$("#date_picker").val("{$inputFields.Date}");
		$("#start_time").val("{$inputFields.Start}");
		$("#finish_time").val("{$inputFields.End}");
		$("#"+String.toLowerCase("{$inputFields.MeetingType}")).prop("checked", true);
		$("#num_volunteers").val("{$inputFields.NumVolunteers}");
		$("#description").val("{$inputFields.Description|addslashes nofilter}");
		formEnable(true);
		checkMeeting();
		checkSelect();
{else}
		formEnable(false);
{/if}
	});
</script>
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Schedule Meeting</h1>
	<br />

	<div id="form_background">
		<div id="form_left">
			<form method="POST" action="{$baseUrl}post.php" onsubmit="return validateForm();">
				<input type="hidden" name="source" value="schedule_meeting">
				<input type="hidden" name="token" value="{$token}" />
{if $inputFields}
				<input type="hidden" name="eventId" value="{$inputFields.MeetingID}" />
{/if}
				<label class="label">Location<br />
				<span class="small">&nbsp;</span>
				</label>
				<select id="location" class="input" name="location" onchange="changeLocation();">
					<option selected="selected" value="">-- Select Location --</option>
					{foreach $locations as $l}
					<option value="{$l["LocationID"]}">{$l["LocationName"]}</option>
					{/foreach}
				</select><br />

				<label class="label">Date<br />
					<span class="small">&nbsp;</span>
				</label>
				<input class="input" type="text" name="date" id="date_picker" onchange="checkSelect();" /><br />

				<label class="label">Start Time<br />
					<span class="small">&nbsp;</span>
				</label>
				<input class="input" type="text" name="start" id="start_time" value="" onchange="checkSelect();" /><br />

				<label class="label">Finish Time<br />
					<span class="small">&nbsp;</span>
				</label>
				<input class="input" type="text" name="finish" id="finish_time" value="" onchange="checkSelect();" /><br />

				<label class="label">Meeting Type<br />
					<span class="small">&nbsp;</span>
				</label>
				<input onclick="checkMeeting()" id="interview" type="radio" name="meetingType" value="Interview" checked="1">&nbsp;<label>Interview</label>
				<input onclick="checkMeeting()" id="rehearsal" type="radio" name="meetingType" value="Rehearsal">&nbsp;<label>Rehearsal</label><br />

				<div id="volunteer_info">
					<label id="num_volunteers_label" class="label"># Volunteers<br />
						<span class="small">&nbsp;</span>
					</label>
					<input class="input" type="text" name="numOfVolunteers" id="num_volunteers" value="0" /><br />
				</div>

				<label class="label">Description<br />
					<span class="small">&nbsp;</span>
				</label>
				<textarea id="description" class="input" name="description"></textarea><br /><br />

				<center>
					<input type="submit" value="Submit" id="submit" name="submit" />
					&nbsp;|&nbsp;
					<input type="submit" value="Cancel" id="submit" name="cancel" />
				</center>

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

		//var startTime = Math.round((new Date(date+" "+start)).getTime() / 1000);
		//var endTime = Math.round((new Date(date+" "+end)).getTime() / 1000);

		if (date != "")
		{
			var day = new Date(date);
			$("#calendar").fullCalendar("gotoDate", day.getFullYear(), day.getMonth(), day.getDate());
		}
		if (date != "" && start != "" && end != "")
		{
			$("#calendar").fullCalendar("select", new Date(date+" "+start), new Date(date+" "+end), false);
		}
	}

	function toTimestamp(year,month,day,hour,minute,second)
	{
		 var datum = new Date(Date.UTC(year,month-1,day,hour,minute,second));
		 return datum.getTime()/1000;
	}

	$(function() {
		$( "#date_picker" ).datepicker({
/*			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true*/
		});
		$("#start_time").timepicker({
			ampm: true,
			hourMin: 6,
			hourMax: 21,
			timeFormat: "h:mm TT"
		});
		$("#finish_time").timepicker({
			ampm: true,
			hourMin: 6,
			hourMax: 21,
			timeFormat: "h:mm TT"
		});
	});

	function checkMeeting()
	{
/*		if ($("#interview").is(":checked"))
			$("#volunteer_info").show();
		else
			$("#volunteer_info").hide();*/

		if ($("#interview").is(":checked"))
		{
			$("#num_volunteers").prop("disabled", false);
			$("#num_volunteers").removeClass("inputDisabled");
			$("#num_volunteers_label").removeClass("textDisabled");
		}
		else
		{
			$("#num_volunteers").prop("disabled", true);
			$("#num_volunteers").addClass("inputDisabled");
			$("#num_volunteers_label").addClass("textDisabled");
		}
	}
	checkMeeting();

	function getUrlVars()
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split("=");
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
