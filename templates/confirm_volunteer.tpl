{extends file="template.tpl"}
{block name="page_title"}Volunteer participation{/block}

{block name="page_head"}
	<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Volunteer participation</h1><br />

	<label class="label">Date:</label>
	<span>Wedensday, November 30th, 2011</span><br />

	<label class="label">Time:</label>
	<span>From 2:00 PM to 3:00 PM</span><br />

	<label class="label">Meeting Type:</label>
	<span>Interview</span><br />
	<br />

	<p>Please mark the name(s) of the volunteer(s) that showed up to the above meeting.</p><br />

	<form>
		<label class="label">Shawn LeMaster</label>
			<span class="small">&nbsp;</span>
			<input type="checkbox" name="vehicle" value="1" /><br />
		<label class="label">John Doe</label>
			<span class="small">&nbsp;</span>
			<input type="checkbox" name="vehicle" value="2" />
	</form> 
	<br />

	<center>
		<input type="submit" value="Confirm" id="submit" />
		<input type="submit" value="Cancel" id="submit" />
	</center><div class="spacer"></div>
</div>

{/block}
