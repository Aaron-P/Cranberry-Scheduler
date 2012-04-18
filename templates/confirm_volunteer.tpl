{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Volunteer participation{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Volunteer participation</h1><br />
	<form method="POST" action="{$baseUrl}post.php">
		<input type="hidden" name="source" value="confirm_volunteer">
		<input type="hidden" name="token" value="{$token}" />

{foreach $meetings as $m}
		<label class="label">Date:</label>
		<span>{$m.StartTime}</span><br />

		<label class="label">Time:</label>
		<span>{$m.StartTime} to {$m.EndTime}</span><br />

		<p>Please mark the name(s) of the volunteer(s) that showed up to the above meeting.</p><br />

	{foreach $m.Volunteers as $v}
		<label class="label">{$v.FirstName} {$v.LastName}</label>
		<span class="small">&nbsp;</span>
		<input type="checkbox" name="volunteers[]" value="{$m.MeetingID}|{$v.PersonID}" /><br />
	{/foreach}
{/foreach}
		<br />

		<center>
			<input type="submit" value="Confirm" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>
	<br />

	<div class="spacer"></div>
</div>

{/block}
