(function($, window, document) {

$(function(){});

var body = $('body');

// Handle login form submition
body.on('submit', '#form-login', function () {
	var form = $(this);
	var redirect = form.find('[name="_redirect"]').val();
	var submit = form.find('button[type="submit"]');
	var data = {
		email: form.find('[name="email"]').val(),
		password: form.find('[name="password"]').val()
	};

	andila.html.btnLoading(submit);

	andila.api.post(form.attr('action'), data)
		.done(function (res) {
			var data = {access_token: res.token};
			andila.helper.redirect(redirect, data);

			andila.html.btnSuccess(submit);
		})
		.fail(function (xhr) {
			var data = {errors: xhr.responseText};
			andila.helper.redirect(redirect, data);

			andila.html.btnRepeat(submit);
		});

	return false;
})

}(window.jQuery, window, document));
