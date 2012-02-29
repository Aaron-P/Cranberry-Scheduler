{extends file="template.tpl"}
{block name="page_title"}Meeting Overview{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
<link rel='stylesheet' type='text/css' href='css/meeting_overview.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1 style="text-align:center;">Meeting Overview</h1><br />

	<label class="label">Date:</label>
	<span>Wedensday, November 30th, 2011</span><br />

	<label class="label">Time:</label>
	<span>From 2:00 PM to 3:00 PM</span><br />

	<label class="label">Meeting Type:</label>
	<span>Interview</span><br />

	<label class="label">Volunteer Type:</label>
	<span>Manual</span><br />

	<h3 style="text-align: center;">Volunteer Information</h3>

	<label class="label">Name:</label>
	<span>Jon Doe</span><br />

	<label class="label">e-ID:</label>
	<span>jdoe</span><br />

	<label class="label">Email:</label>
	<span>test@example.com</span><br />

	<label class="label">Phone #:</label>
	<span>618-555-5555</span><br />
	<br />

	<label class="label">Name:</label>
	<span>Bob Smith</span><br />

	<label class="label">e-ID:</label>
	<span>bsmith</span><br />

	<label class="label">Email:</label>
	<span>bsmith@something.net</span><br />

	<label class="label">Phone #:</label>
	<span>618-555-1234</span><br />

	<div class="spacer"></div>
</div>
<br />

<div id="stylized2" class="myform">
	<a href="#">Edit Meeting</a>
</div>
{/block}
