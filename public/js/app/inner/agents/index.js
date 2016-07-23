(function($, window, document) {

/*
 * Global variables
 */
var box = $('#andila-agents-data');
var modal = $('#modal-agent-show');
var btnDelete = $('#btn-agent-delete');
var datatable = box.find('.andila-datatable').DataTable(andila.datatable.defaultOptions);

/*
 * Load agents data from the API
 */
function loadAgents(data) {

	var realData = data || {};

	if (! realData.hasOwnProperty('limit')) {
		realData.limit = 999;
	}

	return andila.api.get(andila.apiLocation + '/agents', realData);

}

/*
 * Fetch agents data to datatable
 */
function fetchAgents(res) {

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
 * Get a agent data from API
 */
function singleAgent(id, data) {

	return andila.api.get(andila.apiLocation + '/agents/' + id, data);

}

/*
 * Get one agent's this month reported orders
 */
function getReportedOrders(agent) {

	// Get the this month range
	var thisMonthRange = moment().startOf('month').format('YYYY-MM-DD') + '_' + moment().endOf('month').format('YYYY-MM-DD');

	return andila.api.get(andila.apiLocation + '/reports', {
		agent: agent,
		range: thisMonthRange,
		reported: 1,
		limit: 999,
	});
}

$(function(){
	
	// Load agents data into datatable
	loadAgents().done([fetchAgents, function () {
		
		// Remove the overlay in the datatable box
		andila.box.finish(box);

	}]).fail(andila.helper.errorify);

	// Set popover to delete agent button
	btnDelete.popover({
		html: true,
		placement: 'top',
		content: function () {
			return andila.html.div([
				andila.html.p('Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Hapus Agen', 'btn btn-sm btn-danger', 'btn-agent-confirm-delete'), 'text-right'),
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
		modal.find('#btn-agent-activate').hide();
		modal.find('#btn-agent-deactivate').show().attr('data-id', id);
	} else {
		modal.find('#btn-agent-deactivate').hide();
		modal.find('#btn-agent-activate').show().attr('data-id', id);
	}

	singleAgent(id, {
		subagents: 1,
	}).done(function (res) {
		
		// Put subagent statistic
		modal.find('#stat-subagents').text(res.model.subagents.length);
		modal.find('#stat-contract-value').text(numeral(res.model.contract_value * moment().daysInMonth()).format('0.00a'));

		andila.gmap.initialize(function () {
			var latLng = res.model.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});

	}).fail(andila.helper.errorify).always(function () {

		getReportedOrders(id).done(function (res) {

			var reported = 0;

			for (var report of res.results) {
				reported += report.allocated_qty;
			}

			// Put allocation statistic
			modal.find('#stat-reported').text(numeral(reported).format('0.00a'));

		}).fail(andila.helper.errorify);

	});

	modal.find('.agent-id').text('#' + id);
	modal.find('.agent-name').text(tr.find('td:eq(1)').text());
	modal.find('.agent-province').text(tr.find('td:eq(3)').text());
	modal.find('#btn-agent-show').attr('href', andila.appLocation + '/agents/' + id);
	modal.find('#btn-agent-edit').attr('href', andila.appLocation + '/agents/' + id + '/edit');
	modal.find('#btn-agent-delete').attr('data-id', id);
	modal.modal('show');

});

// Handle click on confirm delete button
body.on('click', '#btn-agent-confirm-delete', function () {

	var id = modal.find('#btn-agent-delete').attr('data-id');

	andila.api.delete(andila.apiLocation + '/agents/' + id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Agen "' + res.model.name + '" telah berhasil dihapus!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadAgents().done([fetchAgents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);

});

// Handle click on activate button
body.on('click', '#btn-agent-activate', function () {

	var id = $(this).attr('data-id');

	andila.api.post(andila.apiLocation + '/agents/' + id + '/activate').done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Agen "' + res.model.name + '" telah berhasil diaktifkan!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadAgents().done([fetchAgents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);
	
});

// Handle click on deactivate button
body.on('click', '#btn-agent-deactivate', function () {

	var id = $(this).attr('data-id');

	andila.api.post(andila.apiLocation + '/agents/' + id + '/deactivate').done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Agen "' + res.model.name + '" telah berhasil dinonaktifkan!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadAgents().done([fetchAgents, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);

});

}(window.jQuery, window, document));
