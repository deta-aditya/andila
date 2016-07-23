(function($, window, document) {

$(function(){});

var body = $('body');

// Handle create user form submition
body.on('submit', '#form-user-create', function()
{
	var username = $(this).find('[name="username"]').val();
	var email = $('<input>');

	email.attr({type: 'hidden', name: 'email', value: username + '@andila.dist'});
	$(this).append(email);
})

}(window.jQuery, window, document));
