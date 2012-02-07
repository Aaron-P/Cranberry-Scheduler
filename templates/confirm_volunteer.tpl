{extends file="template.tpl"}
{block name="page_title"}Volunteer participation{/block}

{block name="page_head"}
		<style type="text/css">

			.input {
				border: 1px solid #006;
				background: #ffc;
			}
			.button {
				border: 1px solid #006;
				background: #9cf;
			}
			.label {
				display: block;
				width: 210px;
				float: left;
				margin: 2px 4px 6px 4px;
				text-align: right;
			}
			br {
				clear: left;
			}

			label .small {
				color: #666666;
				font-size: 11px;
				font-weight: normal;
				text-align: right;
				width: 140px;
			}

		</style>
{/block}

{block name="page_content"}
<div id="stylized" class="myform" style="border:2px solid;background-color:#F2FDED;margin-left:auto;margin-right:auto;padding:10px;width:500px;position:relative;margin-top:100px;">
<h1 style="text-align:center;">Volunteer participation</h1><br />
<hr /><br />

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
