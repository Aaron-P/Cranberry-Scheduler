{extends file="template.tpl"}
{block name="page_title"}Meetings{/block}

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
<h1 style="text-align:center;">Scheduled Meetings</h1><br />
<hr /><br />
<p><span><a href="meeting_overview__.htm">Rehearsal</a> - 12/05/11 @ 4:00 PM to 5:15 PM - Nam aliquam dolor id ipsum luctus et tempus odio euismod. Praesent non lacus eget libero sollicitudin...</span></p><br />
<p><span><a href="meeting_overview___.htm">Interview</a> - 12/09/11 @ 1:15 PM to 2:30 PM - Donec id neque sapien, vel vehicula elit. Pellentesque interdum mattis pellentesque. Sed gravida leo vel nisi...</span></p><br />
</div>
{/block}
