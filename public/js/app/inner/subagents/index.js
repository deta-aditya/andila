(function($, window, document) {

/*
 * Global variables
 */
var box = $('#andila-subagents-data');
var modal = $('#modal-subagent-show');
var btnDelete = $('#btn-subagent-delete');
var datatable = box.find('.andila-datatable').DataTable(andila.datatable.defaultOptions);

/*
 * Load subagents data from the API
 */
function loadSubagents(data) {

	var realData = data || {};

	if (! realData.hasOwnProperty('limit')) {
		realData.limit = 999;
	}

	return andila.api.get(andila.apiLocation + '/subagents', realData);

}

/*
 * Fetch subagents data to datatable
 */
function fetchSubagents(res) {

	andila.datatable.clear(datatable);

	for (var data of res.results) {
		var active = data.active ? 'Ya' : andila.html.span('Tidak', 'text-red').prop('outerHTML');

		andila.datatable.populate(datatable, [
			data.id, data.name, data.phone, 
			data.address.province, active,
			data.created_at, data.updated_at
		]);
	}

}

/*
 * Get a subagent data from API
 */
function singleSubagent(id, data) {

	return andila.api.get(andila.apiLocation + '/subagents/' + id, data);

}

/*
 * Get one subagent's this month reported orders
 */
function getReportedOrders(subagent) {

	// Get the this month range
	var thisMonthRange = moment().startOf('month').format('YYYY-MM-DD') + '_' + moment().endOf('month').format('YYYY-MM-DD');

	return andila.api.get(andila.apiLocation + '/reports', {
		subagent: subagent,
		range: thisMonthRange,
		limit: 999,
	});
}

$(function(){
	
	// Load subagents data into datatable
	loadSubagents().done([fetchSubagents, function () {
		
		// Remove the overlay in the datatable box
		andila.box.finish(box);

	}]).fail(andila.helper.errorify);

	// Set popover to delete subagent button
	btnDelete.popover({
		html: true,
		placement: 'top',
		content: function () {
			return andila.html.div([
				andila.html.p('Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Hapus Subagen', 'btn btn-sm btn-danger', 'btn-subagent-confirm-delete'), 'text-right'),
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
body.on('change', '[data-table="search-active"]', function () {
	andila.datatable.search(datatable, this.value, 4);
});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {
	andila.datatable.search(datatable, this.value);
});

// Handle click on clickable row
body.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();
	var active = tr.find('td:eq(4)').text();

	if (active === 'Ya') {
		modal.find('#btn-subagent-activate').hide();
		modal.find('#btn-subagent-deactivate').show().attr('data-id', id);
	} else {
		modal.find('#btn-subagent-deactivate').hide();
		modal.find('#btn-subagent-activate').show().attr('data-id', id);
	}

	singleSubagent(id, {}).done(function (res) {
		
		modal.find('#stat-contract-value').text(res.model.contract_value);

		andila.gmap.initialize(function () {
			var latLng = res.model.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});

	}).fail(andila.helper.errorify).always(function () {

		getReportedOrders(id).done(function (res) {

			var allocated = 0;
			var reported = 0;

			for (var report of res.results) {
				allocated += report.allocated_qty;

				if (report.reported_at !== null) {
					reported += report.allocated_qty;
				}
			}

			modal.find('#stat-allocated').text(numeral(allocated).format('0.00a'));
			modal.find('#stat-reported').text(numeral(reported).format('0.00a'));

		}).fail(andila.helper.errorify);

	});

	modal.find('.subagent-id').text('#' + id);
	modal.find('.subagent-name').text(tr.find('td:eq(1)').text());
	modal.find('.subagent-province').text(tr.find('td:eq(3)').text());
	modal.find('#btn-subagent-show').attr('href', andila.appLocation + '/subagents/' + id);
	modal.find('#btn-subagent-edit').attr('href', andila.appLocation + '/subagents/' + id + '/edit');
	modal.find('#btn-subagent-delete').attr('data-id', id);
	modal.modal('show');

});

// Handle click on confirm delete button
body.on('click', '#btn-subagent-confirm-delete', function () {

	var id = modal.find('#btn-subagent-delete').attr('data-id');

	andila.api.delete(andila.apiLocation + '/subagents/' + id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Subagen "' + res.model.name + '" telah berhasil dihapus!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadSubagents().done([fetchSubagents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);

});

// Handle click on activate button
body.on('click', '#btn-subagent-activate', function () {

	var id = $(this).attr('data-id');

	andila.api.post(andila.apiLocation + '/subagents/' + id + '/activate').done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Subagen "' + res.model.name + '" telah berhasil diaktifkan!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadSubagents().done([fetchSubagents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);
	
});

// Handle click on deactivate button
body.on('click', '#btn-subagent-deactivate', function () {

	var id = $(this).attr('data-id');

	andila.api.post(andila.apiLocation + '/subagents/' + id + '/deactivate').done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Subagen "' + res.model.name + '" telah berhasil dinonaktifkan!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadSubagents().done([fetchSubagents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);

});

}(window.jQuery, window, document));
