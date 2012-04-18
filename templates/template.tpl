{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>{block name="page_title"}{/block}</title>
		<link type="text/css" rel="stylesheet" href="{$baseUrl}css/base_style.css" />
		<link type="text/css" rel="stylesheet" href="{$baseUrl}css/jquery.fancybox-1.3.4.css" />
		<script type="text/javascript" src="{$baseUrl}js/jquery-1.6.4.js"></script>
		<script type="text/javascript" src="{$baseUrl}js/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="{$baseUrl}js/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="{$baseUrl}js/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="{$baseUrl}js/resize_fix.js"></script>
{if $confirmVolunteers}
		<script type="text/javascript">
		$(document).ready(function() {
			$.fancybox.init();
			$.fancybox({
				"href"              : "{$baseUrl}index.php?page=confirm_volunteer",
				"width"				: 541,
				"height"			: 300,
				"autoScale"     	: false,
				"transitionIn"		: "none",
				"transitionOut"		: "none",
				"type"				: "iframe"
			});
			$.fancybox.resize();
		});
		</script>
{/if}
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
