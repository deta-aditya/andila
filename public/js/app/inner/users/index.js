(function($, window, document) {

/*
 * Global variables
 */
var box = $('#andila-users-data');
var modal = $('#modal-user-show');
var btnDelete = $('#btn-user-delete');
var datatable = box.find('.andila-datatable').DataTable(andila.datatable.defaultOptions);

/*
 * Load users data from the API
 */
function loadUsers(data) {

	var realData = data || {};

	if (! realData.hasOwnProperty('limit')) {
		realData.limit = 999;
	}

	return andila.api.get(andila.apiLocation + '/users', realData);

}

/*
 * Fetch users data to datatable
 */
function fetchUsers(res) {

	andila.datatable.clear(datatable);

	for (var data of res.results) {

		var handling = data.handling === 'Agent' ? 'Agen' : (
			data.handling === 'Subagent' ? 'Subagen' : data.handling
		);

		var lastLogin = data.last_login ? moment(data.last_login).format('dddd, D MMMM YYYY H:mm:ss') : 'Belum Pernah';
		var createdAt = data.created_at ? moment(data.created_at).format('dddd, D MMMM YYYY H:mm:ss') : '-';

		andila.datatable.populate(datatable, [
			data.id, data.email, handling, lastLogin, createdAt,
			moment(data.updated_at).format('dddd, D MMMM YYYY H:mm:ss'),
		]);
	}

}

/*
 * Get a user data from API
 */
function singleUser(id, data) {

	return andila.api.get(andila.apiLocation + '/users/' + id, data);

}

$(function(){
	
	// Load users data into datatable
	loadUsers().done([fetchUsers, function () {
		
		// Remove the overlay in the datatable box
		andila.box.finish(box);

	}]).fail(andila.helper.errorify);

	// Set popover to delete user button
	btnDelete.popover({
		html: true,
		placement: 'bottom',
		content: function () {
			return andila.html.div([
				andila.html.p('Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Hapus Pengguna', 'btn btn-sm btn-danger', 'btn-user-confirm-delete'), 'text-right'),
			]);
		}
	});

});

var body = $('body');

// Handle change on data-table="length"
body.on('change', '[data-table="length"]', function () {
	andila.datatable.len(datatable, this.value);
});

// Handle change on data-table="search-type"
body.on('change', '[data-table="search-type"]', function () {
	andila.datatable.search(datatable, this.value, 2);
});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {
	andila.datatable.search(datatable, this.value);
});

// Handle click on clickable row
body.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();

	singleUser(id, {handleable: 1}).done(function (res) {

		var handling = res.model.handling.toLowerCase() + 's';

		if (res.model.handleable) {
			modal.find('#btn-user-handling').attr('href', andila.appLocation + '/' + handling + '/' + res.model.handleable.id);
		} else {
			modal.find('#btn-user-handling').attr('href', '#');
		}

	}).fail(andila.helper.errorify);

	modal.find('.user-id').text('#' + id);
	modal.find('.user-name').text(tr.find('td:eq(1)').text());
	modal.find('#btn-user-edit').attr('href', andila.appLocation + '/users/' + id + '/edit');
	modal.find('#btn-user-delete').attr('data-id', id);
	modal.modal('show');

});

// Handle click on confirm delete button
body.on('click', '#btn-user-confirm-delete', function () {

	var id = modal.find('#btn-user-delete').attr('data-id');

	andila.api.delete(andila.apiLocation + '/users/' + id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Pengguna "' + res.model.email + '" telah berhasil dihapus!'));
		andila.box.loading(box);

		loadUsers().done([fetchUsers, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify).always(function () {
		modal.modal('hide');
	});

});

}(window.jQuery, window, document));
