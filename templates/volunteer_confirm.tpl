{extends file="template.tpl"}
{block name="page_title"}Confirm{/block}

{block name="page_head"}
{/block}

{block name="page_content"}
<div id="stylized" class="myform" style="border:2px solid;background-color:#F2FDED;margin-left:auto;margin-right:auto;padding:10px;width:500px;position:relative;margin-top:106px;">
	<form method="POST" action="volunteer_confirm_success.htm">
		<h2 style="text-align:center;">Participation Confirmation</h2>
		<div style="text-align: center;">
		<span >November 6, 2011 at 3:00 PM</span>
		</div><br />
		<hr />

		<h3 style="text-align: center;">Project Description</h3>
		<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In laoreet viverra erat sit amet cursus. In eget urna arcu, nec vulputate sapien. Nullam varius diam non mi luctus auctor. Ut ut fringilla nisi. Mauris ut velit in lacus tempor imperdiet. In volutpat congue nisl quis vulputate. Nullam consectetur pretium quam nec cursus. Pellentesque pretium lacinia augue sed venenatis. Etiam lacinia congue eros, eu luctus augue eleifend at. Donec vel eros eget quam condimentum bibendum sed eu sem. Phasellus non tellus neque.</span>
		<br /><br />
		<hr />

		<h3 style="text-align: center;">Recording Disclaimer</h3>
		<div style="text-align: center;">
		<input type="checkbox" name="video" onchange="document.getElementById('submit').disabled = !this.checked;" />&nbsp;<label>I agree to be recorded for the purposes of this study.</label>
		</div>
		<br />



		<center><input type="submit" value="Accept" id="submit" disabled="1" />&nbsp;&nbsp;|&nbsp;&nbsp;<input type="button" value="Decline" id="submit" /></center>
		<div class="spacer"></div>

	</form>
</div>
{/block}
