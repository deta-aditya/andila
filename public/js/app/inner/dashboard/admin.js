(function($, window, document) {

/*
 * Global variables
 */

var annual = {

	chartBox: $('#andila-annual-box'),
	chartCanvas: $('#chart-annual-dist-chart'),
	chart: null,
	data: {},

	load: function() {

		return andila.api.get(andila.apiLocation + '/schedules', {
			sort: 'scheduled_date:asc',
			limit: 999,
		});

	},

	loadFetchAndChartify: function(callback) {

		andila.box.loading(annual.chartBox);

		annual.load().done(function (res) {

			for (var schedule of res.results) {
				annual.data[schedule.id] = schedule;
			}

			annual.chartify();

			if (callback) {
				callback();
			}

		}).fail(andila.helper.errorify).always(function () {
			andila.box.finish(annual.chartBox);
		});

	},

	chartify: function() {

		var chunk = [];
		var labelsForChart = [];
		var dataForChart = {
			backgroundColor: 'rgba(221,75,57, 0.5)',
			label: 'Tabung',
		};

		var previousMonth = null;

		// It is so painful to think about this
		// logic so please don't violate :(
		for (var id in annual.data) {
			
			var scheduledDate = moment(annual.data[id].scheduled_date);
			var scheduledMonth = scheduledDate.format('MMM YY');

			if (annual.data[id].order === null) {
				continue;
			}

			if (previousMonth === scheduledMonth) {
				chunk.push(chunk.pop() + annual.data[id].order.quantity);
				continue;
			}

			chunk.push(annual.data[id].order.quantity);
			labelsForChart.push(scheduledMonth);

			previousMonth = scheduledMonth;
		}

		dataForChart.data = chunk;

		andila.chart.initializeBar(annual.chart, annual.chartCanvas, labelsForChart, [dataForChart]);

	},

};

var markers = {

	mapBox: $('#andila-map-box'),
	stationData: [],
	agentData: [],
	subagentData: [],

	loadStations: function() {
		return andila.api.get(andila.apiLocation + '/stations', {
			limit: 999,
		});
	},

	loadAgents: function() {
		return andila.api.get(andila.apiLocation + '/agents', {
			limit: 999,
		});
	},

	loadSubagents: function() {
		return andila.api.get(andila.apiLocation + '/subagents', {
			limit: 999,
		});
	},

	loadAll: function(callback) {
		markers.loadStations().done(function (res) {

			for (var station of res.results) {
				markers.stationData.push(station);
			}

			markers.loadAgents().done(function (res) {

				for (var agent of res.results) {
					markers.agentData.push(agent);
				}
				
				markers.loadSubagents().done(function (res) {

					for (var subagent of res.results) {
						markers.subagentData.push(subagent);
					}

					if (callback) {
						callback();
					}					

				}).fail(andila.helper.errorify);
			}).fail(andila.helper.errorify);		
		}).fail(andila.helper.errorify);
	},

	locateAll: function() {
		andila.gmap.initialize(function () {
			
			for (var station of markers.stationData) {
				andila.gmap.markAndInfo(
					new google.maps.LatLng(station.location[0], station.location[1]),
					markers.createInfoString(station, 'Station')
				);
			} 
			
			for (var agent of markers.agentData) {
				andila.gmap.markAndInfo(
					new google.maps.LatLng(agent.location[0], agent.location[1]),
					markers.createInfoString(agent, 'Agent')
				);
			} 
			
			for (var subagent of markers.subagentData) {
				andila.gmap.markAndInfo(
					new google.maps.LatLng(subagent.location[0], subagent.location[1]),
					markers.createInfoString(subagent, 'Subagent')
				);
			} 

		});
	},

	loadAndLocateAll: function() {
		markers.loadAll(markers.locateAll);
	},

	createInfoString: function(data, type) {
		return andila.html.div([
			andila.html.heading(4, [data.name, ' ', $('<small>').text(type)]),
			andila.html.p(data.address.province),
			andila.html.p($('<b>').text(data.location[0] + ',' + data.location[1]))
		], 'text-center').prop('outerHTML');
	},

}

var orders = {

	datatable: $('#andila-orders-datatable').DataTable(andila.datatable.minimalOptions),
	acceptBtn: $('#btn-order-accept'),
	modal: $('#modal-order'),
	selectedRow: null,

	single: function(id) {

		data = {
			station: 1,
			agent: 1
		};

		return andila.api.get(andila.apiLocation + '/schedules/' + id, data);

	},

	accept: function(id) {
		return andila.api.post(andila.apiLocation + '/orders/' + id + '/accept', {}).fail([function () {
			andila.html.btnRepeat('#btn-order-confirm-accept');
		}, andila.helper.errorify]).always(function () {
			orders.modal.modal('hide');
		});
	},

};

var subagents = {

	datatable: $('#andila-subagents-datatable').DataTable(andila.datatable.minimalOptions),
	activateBtn: $('#btn-subagent-activate'),
	modal: $('#modal-subagent'),
	selectedRow: null,

	single: function(id) {
		return andila.api.get(andila.apiLocation + '/subagents/' + id, {}).fail(andila.helper.errorify);
	},

	activate: function(id) {
		return andila.api.post(andila.apiLocation + '/subagents/' + id + '/activate', {}).fail(
			andila.helper.errorify
		).always(function () {
			subagents.modal.modal('hide');
		});
	},

};

$(function(){

	// Load, fetch and initialize the chart
	annual.loadFetchAndChartify();

	// Set popover to accept order button
	orders.acceptBtn.popover({
		html: true,
		placement: 'bottom',
		content: function () {
			return andila.html.div([
				andila.html.p('Aksi ini dapat memakan waktu yang cukup lama, serta berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Terima Pesanan', 'btn btn-sm btn-danger', 'btn-order-confirm-accept'), 'text-right'),
			]);
		}
	});

});

// Handle click on mapbox
markers.mapBox.on('click', '.overlay', function () {
	
	andila.box.loading(markers.mapBox);
	markers.loadAndLocateAll();
	andila.box.finish(markers.mapBox);

});

// Handle click on clickable row on orders datatable
orders.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();

	orders.selectedRow = tr;

	orders.single(id).done(function (res) {

		orders.modal.find('.order-scheduled-date').text(moment(res.model.scheduled_date).format('D MMMM YYYY'));
		orders.modal.find('.order-station').text(res.model.station.name);
		orders.modal.find('.order-agent').text(res.model.agent.name);

		orders.modal.find('.order-id').text('#' + id);
		orders.modal.find('.order-allocated').text(numeral(res.model.order.quantity).format('0,0') + ' Tabung');

		if (res.model.order !== null) {
			orders.modal.find('#btn-order-accept').attr('data-id', res.model.order.id);
		}

		orders.modal.modal('show');

	}).fail([function () {
		orders.modal.modal('hide');
	}, andila.helper.errorify]);

});

// Handle accept order button click
orders.modal.on('click', '#btn-order-confirm-accept', function () {

	var btn = $(this);
	var id = orders.acceptBtn.attr('data-id');

	andila.html.btnLoading(btn);

	orders.accept(id).done(function () {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Pesanan telah berhasil diterima!'));
		andila.html.btnSuccess(btn);
		andila.datatable.remove(orders.datatable, orders.selectedRow);

	});

	return false;
});

// Handle click on clickable row on subagents datatable
subagents.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();

	subagents.selectedRow = tr;

	subagents.modal.find('#btn-subagent-show').attr('href', andila.appLocation + '/subagents/' + id);
	subagents.modal.find('#btn-subagent-activate').attr('data-id', id);

	subagents.single(id).done(function (res) {
		
		subagents.modal.find('.subagent-id').text(res.model.id);
		subagents.modal.find('.subagent-name').text(res.model.name);
		subagents.modal.find('.subagent-province').text(res.model.address.province);

		subagents.modal.modal('show');

	});

});

// Handle click on activate subagent button
subagents.modal.on('click', '#btn-subagent-activate', function () {

	var id = $(this).attr('data-id');

	subagents.activate(id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Subagen "' + res.model.name + '" telah berhasil diaktifkan!'));
		andila.datatable.remove(subagents.datatable, subagents.selectedRow);

	}).fail(andila.helper.errorify);
	
});

}(window.jQuery, window, document));