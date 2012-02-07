{extends file="template.tpl"}
{block name="page_title"}Add location{/block}

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
<div id="stylized" class="myform" style="border:2px solid;background-color:#F2FDED;margin-left:auto;margin-right:auto;padding:10px;width:500px;position:relative;margin-top:181px;">
<form method="POST" action="create_group_2.htm">
<h1 style="text-align:center;">Add location</h1>
<hr />
<br />

<label class="label">Location name:<br />
<span class="small">&nbsp;</span>
</label>
<input type="password" name="name" id="name" /><br />

<center>
	<input type="submit" value="Create" id="submit" />
	<input type="submit" value="Cancel" id="submit" />
</center>
<div class="spacer"></div>
</div>

{/block}
