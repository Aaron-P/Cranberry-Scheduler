{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Create group{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
	<h1>Create group</h1>
	<br />

	<form method="POST" action{$baseUrl}formHandler.php">
		<input type="hidden" name="source" value="create_group">
		<input type="hidden" name="token" value="{$token}" />

		<label class="label">Class:<br />
			<span class="small">&nbsp;</span>
		</label>
		<select name="class">
			<option>CS 321-001</option>
			<option>CS 321-002</option>
		</select><br />

		<label class="label">Group name:<br />
			<span class="small">&nbsp;</span>
		</label>
		<input type="text" name="groupName" id="name" /><br />

		<label class="label">Members:<br />
			<span class="small">&nbsp;</span>
		</label>
		<select multiple size="10" name="members[]">
			<option>John Doe</option>
			<option>Shawn LeMaster</option>
			<option>De'Liyuon Hamb</option>
			<option>Aaron Papp</option>
			<option>Bob Smith</option>
		</select><br />
		<br />

		<center>
			<input type="submit" value="Create" id="submit" name="submit" />
			<input type="submit" value="Cancel" id="submit" name="cancel" />
		</center>
	</form>
	<div class="spacer"></div>
</div>

{/block}
