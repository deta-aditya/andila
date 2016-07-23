(function($, window, document) {

/*
 * Global variables
 */
var box = $('#andila-stations-data');
var modal = $('#modal-station-show');
var btnDelete = $('#btn-station-delete');
var mapBox = $('#gmap-see-location');
var datatable = box.find('.andila-datatable').DataTable(andila.datatable.defaultOptions);

/*
 * Load stations data from the API
 */
function loadStations(data) {

	var realData = data || {};

	if (! realData.hasOwnProperty('limit')) {
		realData.limit = 999;
	}

	return andila.api.get(andila.apiLocation + '/stations', realData);

}

/*
 * Fetch stations data to datatable
 */
function fetchStations(res) {

	andila.datatable.clear(datatable);

	for (var data of res.results) {
		andila.datatable.populate(datatable, [
			data.id, data.name, data.phone, 
			data.address.province, data.type,
			moment(data.created_at).format('dddd, D MMMM YYYY H:mm:ss'), 
			moment(data.updated_at).format('dddd, D MMMM YYYY H:mm:ss')
		]);
	}

}

/*
 * Get one station's this month allocation
 */
function getThisMonthAllocation(station) {

	// Get the this month range
	var thisMonthRange = moment().startOf('month').format('YYYY-MM-DD') + '_' + moment().endOf('month').format('YYYY-MM-DD');

	return andila.api.get(andila.apiLocation + '/orders', {
		station: station,
		range: thisMonthRange,
		accepted: 1,
		limit: 999,
	});
}

/*
 * Get a station data from API
 */
function singleStation(id, data) {

	return andila.api.get(andila.apiLocation + '/stations/' + id, data);

}

$(function(){
	
	// Load stations data into datatable
	loadStations().done([fetchStations, function () {
		
		// Remove the overlay in the datatable box
		andila.box.finish(box);

	}]).fail(andila.helper.errorify);

	// Set popover to delete station button
	btnDelete.popover({
		html: true,
		placement: 'top',
		content: function () {
			return andila.html.div([
				andila.html.p('Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Hapus Stasiun', 'btn btn-sm btn-danger', 'btn-station-confirm-delete'), 'text-right'),
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

	singleStation(id, {
		agents: 1
	}).done(function (res) {
		
		// Put agent statistic
		modal.find('#stat-agents').text(res.model.agents.length);

		andila.gmap.initialize(function () {
			var latLng = res.model.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});

	}).fail(andila.helper.errorify).always(function () {

		getThisMonthAllocation(id).done(function (res) {

			var allocations = 0;

			for (var order of res.results) {
				allocations += order.quantity;
			}

			// Put allocation statistic
			modal.find('#stat-allocations').text(numeral(allocations).format('0.00a'));

		}).fail(andila.helper.errorify);

	});

	modal.find('.station-id').text('#' + id);
	modal.find('.station-name').text(tr.find('td:eq(1)').text());
	modal.find('.station-province').text(tr.find('td:eq(3)').text());
	modal.find('#btn-station-show').attr('href', andila.appLocation + '/stations/' + id);
	modal.find('#btn-station-edit').attr('href', andila.appLocation + '/stations/' + id + '/edit');
	modal.find('#btn-station-delete').attr('data-id', id);
	modal.modal('show');

});

// Handle click on confirm delete button
body.on('click', '#btn-station-confirm-delete', function () {

	var id = modal.find('#btn-station-delete').attr('data-id');

	andila.api.delete(andila.apiLocation + '/stations/' + id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Stasiun "' + res.model.name + '" telah berhasil dihapus!'));
		andila.box.loading(box);
		modal.modal('hide');

		loadStations().done([fetchStations, function () {
			andila.box.finish(box);
		}]).fail(andila.helper.errorify);

	}).fail(andila.helper.errorify);

});

}(window.jQuery, window, document));
