if ($.browser.msie && $.browser.version < 9.0)
{
	$(window).resize(function()
	{
		$("body").hide();
		$("body").show();
	});
}

function centerAndResize(width, height)
{
	var left = parseInt((screen.availWidth/2) - (width/2));
	var top = parseInt((screen.availHeight/2) - (height/2));
	window.moveTo(left, top);
	if (document.all)
	{
		window.resizeTo(width, height);
	}
	else if (document.layers || document.getElementById)
	{
		if (window.outerHeight < height || window.outerWidth < width)
		{
			window.outerHeight = height;
			window.outerWidth = width;
		}
	}
}
//centerAndResize(1152, 864);