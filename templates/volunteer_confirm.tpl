{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Meeting overview{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Meeting overview</h1><br />
	{include file="meeting_info.tpl"}<br />
	<br />

	<p align="center">Would you like to sign up for this meeting?</p><br />

	<form method="POST" action="{$baseUrl}post.php">
		<input type="hidden" name="source" value="volunteer_confirm">
		<input type="hidden" name="token" value="{$token}" />

		<center>
			<input type="submit" value="Sign up" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>

	<div class="spacer"></div>
</div>

{/block}
