{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{*
{extends file="template.tpl"}
{block name="page_title"}Volunteer participation{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Volunteer participation</h1><br />
	<form method="POST" action="{$baseUrl}post.php">
		<input type="hidden" name="source" value="confirm_volunteer">
		<input type="hidden" name="token" value="{$token}" />

{foreach $meetings as $m}
		<label class="label">Date:</label>
		<span>{$m.StartTime}</span><br />

		<label class="label">Time:</label>
		<span>{$m.StartTime} to {$m.EndTime}</span><br />

		<p>Please mark the name(s) of the volunteer(s) that showed up to the above meeting.</p><br />

	{foreach $m.Volunteers as $v}
		<label class="label">{$v.FirstName} {$v.LastName}</label>
		<span class="small">&nbsp;</span>
		<input type="checkbox" name="volunteers[]" value="{$m.MeetingID}|{$v.PersonID}" /><br />
	{/foreach}
{/foreach}
		<br />

		<center>
			<input type="submit" value="Confirm" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>
	<br />

	<div class="spacer"></div>
</div>

{/block}
*}

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Volunteer participation</title>
		<link type="text/css" rel="stylesheet" href="http://localhost/Cranberry-Scheduler/css/base_style.css" />
		<link type="text/css" rel="stylesheet" href="http://localhost/Cranberry-Scheduler/css/jquery.fancybox-1.3.4.css" />
		<script type="text/javascript" src="http://localhost/Cranberry-Scheduler/js/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="http://localhost/Cranberry-Scheduler/js/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="http://localhost/Cranberry-Scheduler/js/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="http://localhost/Cranberry-Scheduler/js/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="http://localhost/Cranberry-Scheduler/js/resize_fix.js"></script>
		<link rel="stylesheet" type="text/css" href="http://localhost/Cranberry-Scheduler/css/confirm_volunteer.css" />

	</head>
	<body>
<div id="stylized" class="myform">
	<h1>Volunteer participation</h1><br />
	<form method="POST" action="http://localhost/Cranberry-Scheduler/post.php">
		<input type="hidden" name="source" value="confirm_volunteer">
		<input type="hidden" name="token" value="e3034bbcaeff433f81b7c7b68162682404342f22" />

		<label class="label">Date:</label>
		<span>2012-02-02 12:35:00</span><br />

		<label class="label">Time:</label>
		<span>2012-02-02 12:35:00 to 2012-02-02 13:15:00</span><br />

		<p>Please mark the name(s) of the volunteer(s) that showed up to the above meeting.</p><br />

			<label class="label">Louis Green</label>
		<span class="small">&nbsp;</span>
		<input type="checkbox" name="volunteers[]" value="002|0025" /><br />
			<br />

		<center>
			<input type="submit" value="Confirm" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>
</div>
	</body>
</html>