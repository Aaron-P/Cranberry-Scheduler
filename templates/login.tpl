{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}

{block name="page_title"}Login{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
<link rel='stylesheet' type='text/css' href='css/login.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="formHandler.php">
		<input type="hidden" name="postSrc" value="login" />
		<input type="hidden" name="return" value="{$return}" />

		<h1>Log in</h1><br />
		<label>e-ID: </label><input name="username" type="text" class="input" /><br />
		<label>Password: </label><input name="password" type="password" class="input" /><br />
		<br />

		<label>&nbsp;</label><input type="submit" value="Login" name="submit" />
	</form>
</div>
{/block}
