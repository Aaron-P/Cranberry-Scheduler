{extends file="template.tpl"}
{block name="page_title"}Meeting overview{/block}

{block name="page_head"}
	<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized">
	<h1>Meeting overview</h1><br />
	<hr /><br />
	{include file="meeting_info.tpl"}<br />
	<br />

	<p align="center">Would you like to sign up for this meeting?</p><br />

	<center>
		<input type="submit" value="Sign up" id="submit" />
		<input type="submit" value="Cancel" id="submit" />
	</center>
	<div class="spacer"></div>
</div>

{/block}
