{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Meeting Overview{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/meeting_overview.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1 style="text-align:center;">Meeting Overview</h1><br />

	<label class="label">Date:</label>
	<span>{$event.Date}</span><br />

	<label class="label">Time:</label>
	<span>{$event.Start} - {$event.End}</span><br />

	<label class="label">Meeting Type:</label>
	<span>{$event.MeetingType}</span><br />
	<br />

{if $volunteers}
	<h3 style="text-align: center;">Volunteer Information</h3>

	{foreach $volunteers as $v}
		<label class="label">Name:</label>
		<span>{$v.FirstName} {$v.LastName}</span><br />

		<label class="label">e-ID:</label>
		<span>{$v.Eid}</span><br />

		<label class="label">Email:</label>
		<span><a href="mailto:{$v.Eid}@siue.edu">{$v.Eid}@siue.edu</a></span><br />
		<br />
	{/foreach}
{/if}

	<div class="spacer"></div>
</div>
<br />

{if $editable || $signUp}
	<div id="stylized2" class="myform">
	{if $editable}
	<a href="{$baseUrl}index.php?page=schedule_meeting&eventID={$eventId}">Edit Meeting</a>
	{/if}
	{if isset($signup) && $signup}
	<center>
		<p>Volunteer for this event?</p>
		<form method="POST" action="{$baseUrl}post.php">
		<input type="hidden" name="source" value="sign_up">
		<input type="hidden" name="eventId" value={$eventId}>
		<input type="hidden" name="token" value="{$token}" />
		<input type="submit" value="Sign up" id="submit" name="submit" />
		&nbsp;|&nbsp;
		<input type="submit" value="Cancel" id="submit" name="cancel" />
	</center>
	{/if}
	</div>
{/if}

{/block}
