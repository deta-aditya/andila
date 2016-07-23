(function($, window, document) {

$(function(){});

var body = $('body');

// Handle create user form submition
body.on('submit', '#form-user-create', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var data = {
		email: andila.form.get(form, 'email'),
		password: andila.form.get(form, 'password'),
	};

	andila.api.post(andila.apiLocation + '/users', data).done(function (res) {
		andila.html.btnSuccess(submit);
		andila.helper.redirect(andila.appLocation + '/users', {post: res.model.id});
	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;
});

}(window.jQuery, window, document));
