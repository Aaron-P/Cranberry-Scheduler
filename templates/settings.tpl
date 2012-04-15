{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Settings{/block}

{block name="page_head"}
<link type="text/css" rel="stylesheet" href="css/base_style.css">
<link rel="stylesheet" type="text/css" href="css/view_meetings.css" />
<link rel="stylesheet" type="text/css" href="css/settings.css" />
<link type="text/css" rel="stylesheet" href="css/schedule_meeting.css">
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<form method="POST" action="formHandler.php">
		<input type="hidden" name="postSrc" value="settings">

		<h1>Settings</h1><br />
		<div class="centered">
			<input type="checkbox" name="remind" {if $settings["EmailNotify"] === "1"}checked{/if}>
				&nbsp;<label>Send an email reminder
					<select name="reminderTime">
						<option value="1"{if $settings["reminderTime"] === "1"} selected="selected"{/if}>1 hour</option>
						<option value="2"{if $settings["reminderTime"] === "2"} selected="selected"{/if}>2 hours</option>
						<option value="3"{if $settings["reminderTime"] === "3"} selected="selected"{/if}>3 hours</option>
						<option value="4"{if $settings["reminderTime"] === "4"} selected="selected"{/if}>4 hours</option>
						<option value="5"{if $settings["reminderTime"] === "5"} selected="selected"{/if}>5 hours</option>
						<option value="6"{if $settings["reminderTime"] === "6"} selected="selected"{/if}>6 hours</option>
						<option value="12"{if $settings["reminderTime"] === "12"} selected="selected"{/if}>12 hours</option>
						<option value="24"{if $settings["reminderTime"] === "24"} selected="selected"{/if}>24 hours</option>
					</select> before an appointment.
				</label>
		</div><br />

		<label class="label">Email:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input class="input" type="text" name="email" value={if isset($settings["EmailAddress"])}{$settings["EmailAddress"]}{else}{$username}@siue.edu{/if} /><br />

		<!-- <label class="label">Remind me via:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input type="checkbox" name="notifyVia[]" value="email" checked={if $settings["EmailNotify"] === "1"}checked{/if} >&nbsp;<label>Email</label>
		<input type="checkbox" name="notifyVia[]" value="sms" checked={if $settings["SMSNotify"] === "1"}checked{/if}>&nbsp;<label>Text Message (SMS)</label><br /> -->

		<center>
			<input type="submit" value="Save Settings" id="submit" name="submit" />
		</center>

		<div class="spacer"></div>
	</form>
</div>
{/block}
