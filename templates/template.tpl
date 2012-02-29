<!DOCTYPE html>
<html> 
	<head>
		<meta charset="UTF-8" />
		<title>{block name="page_title"}{/block}</title>
		<link type="text/css" rel="stylesheet" href="css/base_style.css" />
		<script type="text/javascript" src="js/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="js/resize_fix.js"></script>
		{block name="page_head"}{/block}
	</head>
	<body>
		<div class="header">
{include file="page_header.tpl"}
		</div>
		<div class="wrapper">
			<div class="spacer_top"></div>
			<div class="content">
				{block name="page_content"}{/block}
			</div>
			<div class="spacer_bottom"></div>
		</div>
		<div class="fake_column"></div>
		<div class="fake_border"></div>
		<div class="footer">
{include file="page_footer.tpl"}
		</div>
	</body>
</html>
