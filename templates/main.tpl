{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}

{block name="page_assigns"}
{assign var="user_bar" value="true"}
{/block}

{block name="page_title"}Main Page{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/fullcalendar.css" />
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/fullcalendar.print.css" media="print" />
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/main.css" />
<script type="text/javascript" src="{$baseUrl}js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="{$baseUrl}js/fullcalendar.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		var calendar = $("#calendar").fullCalendar({
			header: {
				left: "",
				center: "title",
				right: "prev,next today month,agendaDay"/*month,basicWeek,basicDay*/
			},

/*			dayClick: function(date, allDay, jsEvent, view) {
				var timestamp = Math.round((date).getTime() / 1000);
				document.location.href = "shedule_meeting.htm?time=" + timestamp;
			},
*/
			selectable: false,
			selectHelper: false,

			eventClick: function(calEvent, jsEvent, view) {
				window.location.href = "{$baseUrl}index.php?page=meeting_overview&eventID=" + calEvent.id;

				//$.post("event_click.php", { eventID: calEvent.id });
				//,
				// 	function(data) {
				// 		alert(data);
				// 	}
				// );
		        //alert("Event: " + calEvent.title);
		        //alert("Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY);
		        //alert("View: " + view.name);
		        //alert("ID: " + calEvent.id);

		        // change the border color just for fun
		        //$(this).css("border-color", "red");
		    },

			select: function(start, end, allDay) {
				calendar.fullCalendar("unselect");
				window.location.href = "{$baseUrl}index.php?page=shedule_meeting&start="+Math.round((start).getTime()/1000)+"&end="+Math.round((end).getTime()/1000);
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
					$("#calendar").fullCalendar("option", "selectable", false);

				}
				else
					$("#calendar").fullCalendar("option", "selectable", false); //true
			},

			editable: false,

			eventSources: [
				{
					url: "{$baseUrl}feed.php",
					color: "green",
					type: "POST",
		            data: {
		                page: "main"
		            }
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
		<h4>Admin</h4>
		<hr />
		<div id="link_list">
			<p>Locations: <a href="{$baseUrl}index.php?page=add_location">Add</a> | <a href="{$baseUrl}index.php?page=delete_location">Delete</a></p>
			<p>Courses: <a href="{$baseUrl}index.php?page=add_course">Add</a> | <a href="{$baseUrl}index.php?page=main">Delete</a></p>
			<p>Students: <a href="{$baseUrl}index.php?page=main">Add</a> | <a href="{$baseUrl}index.php?page=main">Delete</a></p>
			<p>Groups: <a href="{$baseUrl}index.php?page=main">Add</a> | <a href="{$baseUrl}index.php?page=main">Delete</a></p>
		</div>

		<h4>Research</h4>
		<hr />
		<div id="link_list">
			<p><a href="{$baseUrl}index.php?page=schedule_meeting">Create Meeting</a></p>
			<p><a href="{$baseUrl}index.php?page=view_meetings">View Meetings</a></p>
		</div>

		<h4>Volunteer</h4>
		<hr />
		<div id="link_list">
			<p><a href="{$baseUrl}index.php?page=volunteer_opportunities">Volunteer Opportunities</a></p>
		</div>
	</div>

	<div id="upcoming_events" class="myform">
		<div id="upcoming_events_title">
			<h4>Upcoming Events</h4>
			<hr />
		</div>
		<div id="events_list">
			{if $upcomingEvents}
				{foreach $upcomingEvents as $e}
					<p><a href="{$baseUrl}index.php?page=meeting_overview&eventID={$e.MeetingID}">{$e.Date} - {$e.MeetingType}</a></p>
				{/foreach}
			{else}
				<p style="font-weight:bold;text-align:center;"><br>No<br>Upcoming<br>Events</p><!-- verticle center? -->
			{/if}
		</div>
	</div>
</div>
{/block}
