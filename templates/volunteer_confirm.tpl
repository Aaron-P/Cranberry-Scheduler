{extends file="template.tpl"}
{block name="page_title"}Meeting overview{/block}

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
<h1 style="text-align:center;">Meeting overview</h1><br />
<hr /><br />
{include file="meeting_info.tpl"}<br />
<br />

<p align="center">Would you like to sign up for this meeting?</p><br />

<center>
	<input type="submit" value="Sign up" id="submit" />
	<input type="submit" value="Cancel" id="submit" />
</center>
<div class="spacer"></div>
</div>

{/block}
