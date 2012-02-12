{extends file="template.tpl"}
{block name="page_title"}Add location{/block}

{block name="page_head"}
	<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="create_group_2.htm">
	<h1>Add location</h1>
	<hr />
	<br />

	<label class="label">Location name:<br />
	<span class="small">&nbsp;</span>
	</label>
	<input type="text" name="name" id="name" /><br />

	<center>
		<input type="submit" value="Create" id="submit" />
		<input type="submit" value="Cancel" id="submit" />
	</center>
	<div class="spacer"></div>
</div>

{/block}
