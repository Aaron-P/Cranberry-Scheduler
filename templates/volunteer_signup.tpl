{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Volunteer Signup{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="formHandler.php">
		<input type="hidden" name="postSrc" value="volunteer_signup">
		<h1>Volunteer Signup</h1><br />

		<label class="label">Name:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input type="text" name="name" id="name" value="" /><br />

		<label class="label">e-ID:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input type="text" name="eid" id="name" value="" /><br />

		<label class="label">Class:<br />
			<span class="small">&nbsp;</span>
		</label>
		<select name="class">
			<option>CS 111</option>
			<option>CS 150</option>
		</select><br />

		<center>
			<input type="submit" value="Submit" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>

	<div class="spacer"></div>
</div>
{/block}
