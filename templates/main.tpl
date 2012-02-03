{extends file="template.tpl"}

{block name="page_assigns"}
{assign var="user_bar" value="true"}
{/block}

{block name="page_title"}Main Page{/block}

{block name="page_head"}
		<link rel='stylesheet' type='text/css' href='css/fullcalendar.css' />
		<link rel='stylesheet' type='text/css' href='css/fullcalendar.print.css' media='print' />
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
/*					dayClick: function(date, allDay, jsEvent, view) {
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
		<style type="text/css">
			div#calendar {
				position: relative;
				width: 716px;
				margin-top: 40px;
				margin-left: 20px;
/*				margin-left: auto;
				margin-right: auto;*/
				margin-bottom: 19px;
			}
			div#spacer_calendar {
				height: 1px; /* temporary fix, find the real problem */
			}
			.fc-state-highlight {
				background: #FDE3E7;
			}
			.fc-calendar-body {
				cursor: pointer;
			}
			.fc-basic-day:hover {
				background-color: #E6E6E6;
			}
		</style>
{/block}

{block name="page_content"}
			<div id="calendar"></div>
			<div id="spacer_calendar"></div>


			<div style="position:absolute;/*background-color:#FF0000;*/width:160px;right:0;bottom:0;top:0;">
				<div id="stylized" class="myform" style="line-height:1.5em;text-align:center;border:2px solid;background-color:#F2FDED;padding:10px;width:150px;position:absolute;top:40px;right:20px;height:50px;">
					<p><a href="http://localhost/Cranberry-Scheduler/index.php?page=shedule_meeting">Create Meeting</a></p>
					<p><a href="http://localhost/Cranberry-Scheduler/index.php?page=view_meetings">View Meetings</a></p>
				</div>

				<div id="stylized2" class="myform" style="line-height:1.5em;text-align:center;border:2px solid;background-color:#F2FDED;width:170px;position:absolute;top:134px;right:20px;height:217px;">
					<div style="padding:10px;">
						<span style="font-weight:bold;">Upcoming Events</span>
						<hr />
					</div>
					<div style="/*background-color:#FF0000;*/overflow-y:scroll;height:171px;width:170px;position:relative;left:0;">
						<p><a href="meeting_overview___.htm">Interview - 12/09/11</a></p>
					</div>
				</div>
			</div>
{/block}
