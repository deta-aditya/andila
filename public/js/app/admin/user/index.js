(function($, window, document) {

$(function(){});

var body = $('body');

// Handle Delete Modal Data Presentation
body.on('show.bs.modal', '#modal-delete', function(e)
{
	var data = $(e.relatedTarget).parents('tr');
	var modal = $(this).find('.modal-desc');
	var form = $(this).find('#form-user-delete');
	var action = form.find('[name="action-to"]').val() + '/' + data.find('.user-id').text();

	form.attr('action', action);

	modal.html(
		$('<dl>').addClass('dl-horizontal').append(
			$('<dt>').text('ID'), $('<dd>').text(data.find('.user-id').text()),
			$('<dt>').text('Nama Pengguna'), $('<dd>').text(data.find('.user-username').text()),
			$('<dt>').text('Nama Lengkap'), $('<dd>').text(data.find('.user-first_name').text() + ' ' + data.find('.user-last_name').text()),
			$('<dt>').text('Peran'), $('<dd>').text(data.find('.user-role').text())
		)
	);
})

// Handle Clickable Row
.on('click', 'tr.clickable', function()
{
	var url = $(this).data('href');

	window.location.href = url;
})

}(window.jQuery, window, document));
