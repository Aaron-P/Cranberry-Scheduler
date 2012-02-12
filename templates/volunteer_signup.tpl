{extends file="template.tpl"}
{block name="page_title"}Volunteer Signup{/block}

{block name="page_head"}
	<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="volunteer_signup_2.htm">
	<h1>Volunteer Signup</h1>
	<hr />
	<br />

	<label class="label">e-ID:<br />
	<span class="small">&nbsp;</span>
	</label>
	<input type="text" name="name" id="name" /><br />

	<label class="label">Password:<br />
	<span class="small">&nbsp;</span>
	</label>
	<input type="password" name="name" id="name" /><br />

	<center><input type="submit" value="Submit" id="submit" /></center>
	<div class="spacer"></div>
</div>
{/block}
