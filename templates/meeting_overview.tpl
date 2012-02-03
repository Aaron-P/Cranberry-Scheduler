{extends file="template.tpl"}
{block name="page_title"}Meeting Overview{/block}

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
<h1 style="text-align:center;">Meeting Overview</h1><br />
<hr /><br />

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
<span>test</span><br />

<label class="label">Email:</label>
<span>test@example.com</span><br />

<label class="label">Phone #:</label>
<span>618-555-5555</span><br />

<div class="spacer"></div>

</div>
<br />

<div id="stylized" class="myform" style="text-align:center;border:2px solid;background-color:#F2FDED;margin-left:auto;margin-right:auto;padding:10px;width:500px;position:relative;top:20%;">
<a href="#">Edit Meeting</a>
</div>
{/block}
