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
			limit: 20,
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
			} else if (annual.data[id].order.accepted_date === null) {
				continue;
			}

			if (previousMonth === scheduledMonth) {
				chunk.push(chunk.pop() + annual.data[id].report.quantity);
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
	subagentData: [],

	loadSubagents: function(callback) {
		andila.api.get(andila.apiLocation + '/subagents', {
			limit: 999,
		}).done(function (res) {

			for (var subagent of res.results) {
				markers.subagentData.push(subagent);
			}

			if (callback) {
				callback();
			}					

		}).fail(andila.helper.errorify);
	},

	locateAll: function() {
		andila.gmap.initialize(function () {
			
			for (var subagent of markers.subagentData) {
				andila.gmap.markAndInfo(
					new google.maps.LatLng(subagent.location[0], subagent.location[1]),
					markers.createInfoString(subagent)
				);
			} 

		});
	},

	loadAndLocateAll: function() {
		markers.loadSubagents(markers.locateAll);
	},

	createInfoString: function(data) {
		return andila.html.div([
			andila.html.heading(4, [data.name, ' ', $('<small>').text('Subagen')]),
			andila.html.p(data.address.province),
			andila.html.p($('<b>').text(data.location[0] + ',' + data.location[1]))
		], 'text-center').prop('outerHTML');
	},

}

var schedules = {

	datatable: $('#andila-schedules-datatable').DataTable(andila.datatable.minimalOptions),
	modal: $('#modal-order-create'),
	selectedRow: null,

	single: function(id) {

		data = {
			station: 1,
			agent: 1
		};

		return andila.api.get(andila.apiLocation + '/schedules/' + id, data);

	},

	createOrder: function(id) {
		return andila.api.post(andila.apiLocation + '/schedules/' + id + '/orders', {});
	},

};

$(function(){

	// Load, fetch and initialize the chart
	annual.loadFetchAndChartify();

});

// Handle click on mapbox
markers.mapBox.on('click', '.overlay', function () {
	
	andila.box.loading(markers.mapBox);
	markers.loadAndLocateAll();
	andila.box.finish(markers.mapBox);

});

// Handle click on clickable row on schedules datatable
schedules.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();

	schedules.selectedRow = tr;

	schedules.single(id).done(function (res) {

		// Exactly like the OrderRepository@single.
		// No. I'm not explaining it again.
		var contractValue = res.model.agent.contract_value * moment(res.model.scheduled_date).add(1, 'months').date();

		schedules.modal.find('.schedule-station').text(res.model.station.name);
		schedules.modal.find('.schedule-agent').text(res.model.agent.name);
		schedules.modal.find('.schedule-scheduled-date').text(moment(res.model.scheduled_date).format('D MMMM YYYY'));

		schedules.modal.find('.schedule-id').text('#' + id);
		schedules.modal.find('.schedule-quantity b').text(numeral(contractValue).format('0,0') + ' Tabung');

		if (res.model.schedule !== null) {
			schedules.modal.find('[type="submit"]').attr('data-id', res.model.id);
		}

		schedules.modal.modal('show');

	}).fail([function () {
		schedules.modal.modal('hide');
	}, andila.helper.errorify]);

});

// Handle create order submition
schedules.modal.on('submit', '#form-order-create', function () {

	var btn = andila.form.submitBtn($(this));
	var id = btn.attr('data-id');

	andila.html.btnLoading(btn);

	schedules.createOrder(id).done(function () {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Pesanan berhasil! Silahkan tunggu konfirmasi dari pihak stasiun.'));
		andila.html.btnSuccess(btn);
		andila.datatable.remove(schedules.datatable, schedules.selectedRow);

	}).fail([function () {
		andila.html.btnRepeat(btn);
	}, andila.helper.errorify]).always(function () {
		schedules.modal.modal('hide');
	});

	return false;

});

}(window.jQuery, window, document));