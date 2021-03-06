{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Meetings{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Scheduled Meetings</h1><br />
	{if $upcomingEvents}
		{foreach $upcomingEvents as $e}
			<p><span>
				<h3>{$e.Date}</h3>
				{$e.Start} - {$e.End}<br />
				{$e.MeetingType}: {$e.Description}<br />
				<a href="{$baseUrl}index.php?page=meeting_overview&eventID={$e.MeetingID}">View details</a>
			</span></p>
			<br />
		{/foreach}
	{else}
		<p style="font-weight:bold;text-align:center;">No Upcoming Events<br><br></p><!-- verticle center? -->
	{/if}
</div>
{/block}
