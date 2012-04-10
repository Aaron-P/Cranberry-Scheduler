{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Volunteer opportunities{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Volunteer opportunities</h1><br />

	{foreach $opportunities as $o}
	<h3>{$o['Date']}</h3>
	<table border="0">
		<tr>
			<td width="150" valign="top">{$o['Start']} - {$o['End']}</td>
			<td>{$o['Description']}<br /><a href="index.php?page=meeting_overview&eventID={$o.MeetingID}">Sign up</a></td>
		</tr>
	</table>
	<br />
	{/foreach}

</div>
{/block}
