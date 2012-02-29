{extends file="template.tpl"}
{block name="page_title"}Settings{/block}

{block name="page_head"}
<link rel='stylesheet' type='text/css' href='css/view_meetings.css' />
<link rel='stylesheet' type='text/css' href='css/settings.css' />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="formHandler.php">
		<input type="hidden" name="postSrc" value="settings">

		<h1>Settings</h1><br />
		<label class="label">Notify me via:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input type="checkbox" name="notifyVia[]" value="email" checked="1">&nbsp;<label>Email</label>
		<input type="checkbox" name="notifyVia[]" value="sms">&nbsp;<label>Text Message (SMS)*</label><br />

		<div class="centered">
			<input type="checkbox" name="remind" value="1" checked="1">
				&nbsp;<label>Send reminder
					<select name="reminderTime">
						<option value="1" selected="selected">1 hour</option>
						<option value="2">2 hours</option>
						<option value="3">3 hours</option>
						<option value="4">4 hours</option>
						<option value="5">5 hours</option>
						<option value="6">6 hours</option>
					</select> before an appointment.
				</label>
		</div><br />

		<div class="centered">
			<input type="checkbox" name="remind" value="1">&nbsp;<label>Notify me when a gobal message is posted.</label>
		</div><br />

		<center>
			<input type="submit" value="Save Settings" id="submit" name="submit" />
		</center>

		<div class="spacer"></div>
	</form>
</div>
{/block}
