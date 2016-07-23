(function($, window, document) {

$(function(){});

var body = $('body');

// Handle edit user form submition
body.on('submit', '#form-user-edit', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var data = {};

	if (andila.form.has(form, 'email')) {
		data.email = andila.form.get(form, 'email');
	}

	if (andila.form.has(form, 'password')) {
		data.password = andila.form.get(form, 'password');
		data.password_confirmation = andila.form.get(form, 'password_confirmation');
		data.password_old = andila.form.get(form, 'password_old');
	}

	andila.api.put(andila.apiLocation + '/users/' + andila.form.get(form, 'id'), data).done(function (res) {
		andila.html.btnSuccess(submit);
		andila.helper.redirect(andila.appLocation + '/users', {put: res.model.id});
	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;
});

}(window.jQuery, window, document));
