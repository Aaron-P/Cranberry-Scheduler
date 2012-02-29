{extends file="template.tpl"}

{block name="page_assigns"}
{assign var="user_bar" value="true"}
{/block}

{block name="page_title"}Main Page{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='css/fullcalendar.print.css' media='print' />
<link rel='stylesheet' type='text/css' href='css/main.css' />
<script type='text/javascript' src='js/jquery-ui-1.8.11.custom.min.js'></script>
<script type='text/javascript' src='js/fullcalendar.js'></script>
<script type='text/javascript'>
	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = $('#calendar').fullCalendar({
			header: {
				left: '',
				center: 'title',
				right: 'prev,next today month,agendaDay'/*month,basicWeek,basicDay*/
			},

/*			dayClick: function(date, allDay, jsEvent, view) {
				var timestamp = Math.round((date).getTime() / 1000);
				document.location.href = "shedule_meeting.htm?time=" + timestamp;
			},
*/
			selectable: false,
			selectHelper: false,

			select: function(start, end, allDay) {
				calendar.fullCalendar('unselect');
				window.location.href = "http://localhost/Cranberry-Scheduler/index.php?page=shedule_meeting&start="+Math.round((start).getTime()/1000)+"&end="+Math.round((end).getTime()/1000);
			},

			dayClick: function(date, allDay, jsEvent, view) {
				if (view.name == "month")
				{
					calendar.fullCalendar("gotoDate", date)
					calendar.fullCalendar("changeView", "agendaDay");
				}
			},

			viewChange: function() {
				if ($("#calendar").fullCalendar("getView").name == "month")
				{
					$("#calendar").fullCalendar('option', 'selectable', false);

				}
				else
					$("#calendar").fullCalendar('option', 'selectable', false); //true
			},
			
			editable: false,
			events: [
				{
					title: 'Interview',
					start: new Date(2011, 11, 9, 13, 15),
					end: new Date(2011, 11, 9, 14, 30),
					url: 'meeting_overview___.htm',
					allDay: false
				}
			]
		});
	});
</script>
{/block}

{block name="page_content"}
<div id="calendar"></div>
<div id="spacer_calendar"></div>

<div id="right_side">
	<div id="meeting_box" class="myform">
		<p><a href="http://localhost/Cranberry-Scheduler/index.php?page=shedule_meeting">Create Meeting</a></p>
		<p><a href="http://localhost/Cranberry-Scheduler/index.php?page=view_meetings">View Meetings</a></p>
	</div>

	<div id="upcoming_events" class="myform">
		<div id="upcoming_events_title">
			<h4>Upcoming Events</h4>
			<hr />
		</div>
		<div id="events_list">
			<p><a href="meeting_overview___.htm">Interview - 12/09/11</a></p>
		</div>
	</div>
</div>
{/block}
