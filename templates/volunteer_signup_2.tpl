{extends file="template.tpl"}
{block name="page_title"}Volunteer Signup{/block}

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
<div id="stylized" class="myform" style="border:2px solid;background-color:#F2FDED;margin-left:auto;margin-top:159px;margin-bottom:50px;margin-right:auto;padding:10px;width:500px;position:relative;">
<form method="POST" action="volunteer_signup_success.htm">
<h1 style="text-align:center;">Volunteer Signup</h1>
<hr /><br />
<!--<p>This is the basic look of my form without table</p>-->

<label class="label">Name:<br />
<span class="small">&nbsp;</span>
</label>
<input type="text" name="name" id="name" value="John Doe" /><br />

<label class="label">e-ID:<br />
<span class="small">&nbsp;</span>
</label>
<input type="text" name="name" id="name" value="jdoe" /><br />

<label class="label">Class:<br />
<span class="small">&nbsp;</span>
</label>
<select><option>CS 111</option><option>CS 150</option></select><br />


<!--
calendar
http://arshaw.com/fullcalendar/docs/

-->

<center><input type="submit" value="Submit" id="submit" /><input type="button" value="Cancel" id="submit" /></center>
<div class="spacer"></div>
</div>
{/block}
