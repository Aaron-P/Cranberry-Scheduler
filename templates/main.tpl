/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2012 Aaron Papp                                               *
 *                    De'Liyuon Hamb                                           *
 *                    Shawn LeMaster                                           *
 *               All rights reserved.                                          *
 *                                                                             *
 * Developed by: Web Dynamics                                                  *
 *               Southern Illinois University Edwardsville                     *
 *               https://github.com/Aaron-P/Cranberry-Scheduler                *
 *                                                                             *
 * Permission is hereby granted, free of charge, to any person obtaining a     *
 * copy of this software and associated documentation files (the "Software"),  *
 * to deal with the Software without restriction, including without limitation *
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,    *
 * and/or sell copies of the Software, and to permit persons to whom the       *
 * Software is furnished to do so, subject to the following conditions:        *
 *   1. Redistributions of source code must retain the above copyright notice, *
 *      this list of conditions and the following disclaimers.                 *
 *   2. Redistributions in binary form must reproduce the above copyright      *
 *      notice, this list of conditions and the following disclaimers in the   *
 *      documentation and/or other materials provided with the distribution.   *
 *   3. Neither the names of Web Dynamics, Southern Illinois University        *
 *      Edwardsville, nor the names of its contributors may be used to endorse *
 *      or promote products derived from this Software without specific prior  *
 *      written permission.                                                    *
 *                                                                             *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  *
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    *
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL    *
 * THE CONTRIBUTORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR   *
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,       *
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER *
 * DEALINGS WITH THE SOFTWARE.                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

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

			eventClick: function(calEvent, jsEvent, view) {
				window.location.href = "event_click.php?eventID=" + calEvent.id;

				//$.post("event_click.php", { eventID: calEvent.id });
				//,
				// 	function(data) {
				// 		alert(data);
				// 	}
				// );
		        //alert('Event: ' + calEvent.title);
		        //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
		        //alert('View: ' + view.name);
		        //alert('ID: ' + calEvent.id);

		        // change the border color just for fun
		        //$(this).css('border-color', 'red');
		    },

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

			events: '/Cranberry-Scheduler/event_feed.php',
			eventColor: 'green'
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
		<p><a href="view_meetings.php">View Meetings</a></p>
	</div>

	<div id="upcoming_events" class="myform">
		<div id="upcoming_events_title">
			<h4>Upcoming Events</h4>
			<hr />
		</div>
		<div id="events_list">
			{if $upcomingEvents}
				{foreach $upcomingEvents as $e}
					<p><a href="event_click.php?eventID={$e.MeetingID}">{$e.Date} - {$e.MeetingType}</a></p>
				{/foreach}
			{/if}
		</div>
	</div>
</div>
{/block}
