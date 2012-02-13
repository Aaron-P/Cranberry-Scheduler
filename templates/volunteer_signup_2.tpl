{extends file="template.tpl"}
{block name="page_title"}Volunteer Signup{/block}

{block name="page_head"}
	<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="volunteer_signup_success.htm">
	<h1>Volunteer Signup</h1><br />
	<!--<p>This is the basic look of my form without table</p>-->

	<label class="label">Name:<br />
	<span class="small">&nbsp;</span>
	</label>
	<input type="text" name="name" id="name" value="John Doe" /><br />

	<label class="label">e-ID:<br />
	<span class="small">&nbsp;</span>
	</label>
	<input type="text" name="name" id="name" value="jdoe" /><br />

	<label class="label">Class:<br />
	<span class="small">&nbsp;</span>
	</label>
	<select><option>CS 111</option><option>CS 150</option></select><br />

	<center><input type="submit" value="Submit" id="submit" /><input type="button" value="Cancel" id="submit" /></center>
	<div class="spacer"></div>
</div>
{/block}
