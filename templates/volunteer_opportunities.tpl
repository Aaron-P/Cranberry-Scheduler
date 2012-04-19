{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{*
{extends file="template.tpl"}
{block name="page_title"}Volunteer opportunities{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
{include file="volunteer_opportunities_content.tpl"}
{/block}
*}



{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}

{block name="page_assigns"}
{assign var="user_bar" value="true"}
{/block}

{block name="page_title"}Volunteer Opportunities{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/main.css" />
<script type="text/javascript" src="{$baseUrl}js/jquery-ui-1.8.11.custom.min.js"></script>
{/block}

{block name="page_content"}

<div id="calendar">


<div id="stylized" class="myform">
    <h1>Volunteer opportunities</h1><br />

    {foreach $opportunities as $o}
    <h3>{$o["Date"]}</h3>
    <table border="0">
        <tr>
            <td width="150" valign="top">{$o["Start"]} - {$o["End"]}</td>
            <td>{$o["Description"]}<br /><a href="{$baseUrl}index.php?page=meeting_overview&eventID={$o.MeetingID}">Sign up</a></td>
        </tr>
    </table>
    <br />
    {/foreach}
</div>

</div>

<div id="spacer_calendar"></div>

<div id="right_side">
	<div id="meeting_box" class="myform">
		{if $isTeacher}
		<h4>Admin</h4>
		<hr />
		<div id="link_list">
			<p>Locations: <a href="{$baseUrl}index.php?page=add_location">Add</a> | <a href="{$baseUrl}index.php?page=delete_location">Delete</a></p>
			<p>Courses: <a href="{$baseUrl}index.php?page=add_course">Add</a> | <a href="{$baseUrl}index.php?page=delete_course">Delete</a></p>
			<p>Students: <a href="{$baseUrl}index.php?page=add_student">Add</a> | <a href="{$baseUrl}index.php?page=delete_student">Delete</a></p>
			<p>Groups: <a href="{$baseUrl}index.php?page=add_group">Add</a> | <a href="{$baseUrl}index.php?page=delete_group">Delete</a></p>
		</div>
		{/if}

		{if $isTeacher}
		<h4>Research</h4>
		<hr />
		{/if}
		{if $isTeacher || $isResearcher || $isVolunteer}
		<div id="link_list">
			{if $isTeacher || $isResearcher}
			<p><a href="{$baseUrl}index.php?page=schedule_meeting">Create Meeting</a></p>
			<p><a href="{$baseUrl}index.php?page=volunteer_opportunities">Volunteer Opportunities</a></p>
			{/if}
			<p><a href="{$baseUrl}index.php?page=view_meetings">View Meetings</a></p>
		</div>
		{/if}
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
