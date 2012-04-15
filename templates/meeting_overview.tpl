{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Meeting Overview{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="css/view_meetings.css" />
<link rel="stylesheet" type="text/css" href="css/meeting_overview.css" />
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

<div id="stylized2" class="myform">
	<a href="#">Edit Meeting</a>
</div>
{/block}
