{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Success{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Sign up successful</h1><br />

	{include file="meeting_info.tpl"}<br />
	<br />

	<p>You will be sent a reminder about this event.</p><br />
	<p>To check your reminder settings, Visit the <a href="">Settings</a> page.</p>

	<div class="spacer"></div>
</div>
{/block}
