(function($, window, document) {

$(function(){});

var body = $('body');

// Handle edit user form submition
body.on('submit', '#form-user-edit', function()
{
	var username = $(this).find('[name="username"]').val();
	var email = $('<input>');

	email.attr({type: 'hidden', name: 'email', value: username + '@pertamina.dist'});
	$(this).append(email);
})

}(window.jQuery, window, document));
