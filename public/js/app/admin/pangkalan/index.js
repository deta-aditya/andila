(function($, window, document) {

$(function(){});

var body = $('body');

// Handle Clickable Row
body.on('click', 'tr.clickable', function()
{
	var url = $(this).data('href');

	window.location.href = url;
})

}(window.jQuery, window, document));
