{extends file="template.tpl"}

{block name="page_title"}Login{/block}

{block name="page_head"}
		<style type="text/css">
			.input {
				width: 122px;
			}
			label {
				display: block;
				width: 86px;
				float: left;
				margin: 2px 4px 6px 4px;
				text-align: right;
			}
			br {
				clear: left;
			}
		</style>
{/block}

{block name="page_content"}
				<div style="border:2px solid;background-color:#F2FDED;margin-left:auto;margin-right:auto;padding:10px;width:246px;position:relative;margin-top:228px;">
					<form method="POST" action="main.htm">
						<label>e-ID: </label><input name="username" type="text" class="input" /><br />
						<label>Password: </label><input name="password" type="password" class="input" /><br />
						<label>&nbsp;</label><input type="submit" value="Login" />
					</form>
				</div>
{/block}
