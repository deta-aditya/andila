(function($, window, document) {

/*
 * Global variables
 */

var mapBox = $('#box-profile-map');

var agents = {

	data: null,

	load: function() {
		return andila.api.get($('meta[name="resource-uri"]').attr('content'), {
			subagents: 1,
			stations: 1,
		});
	},

	locate: function() {
		andila.gmap.initialize(function () {
			var latLng = agents.data.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});
	},

	delete: function() {
		return andila.api.delete(andila.apiLocation + '/agents/' + agents.data.id);
	},

};

var stations = {

	box: $('#box-station-data'),
	datatable: $('#andila-station-datatable').DataTable(andila.datatable.defaultOptions),
	toggler: $('a[href="#stations"][data-toggle="tab"]'),
	data: {},

	fetchFromLocal: function() {

		andila.datatable.clear(stations.datatable);

		for (var data of agents.data.stations) {
			
			if (stations.data.hasOwnProperty(data.id)) {
				continue;
			} else {
				stations.data[data.id] = data;
			}

			andila.datatable.populate(stations.datatable, [
				data.id, data.name, data.phone, data.type,
			]);
		}
	},

};

var subagents = {

	box: $('#box-subagent-data'),
	datatable: $('#andila-subagent-datatable').DataTable(andila.datatable.defaultOptions),
	toggler: $('a[href="#subagents"][data-toggle="tab"]'),
	chartBox: $('#box-subagent-chart'),
	chartCanvas: $('#chart-subagent-chart'),
	mapBox: $('#box-subagent-map'),
	chart: null,
	data: {},

	load: function() {

		return andila.api.get(andila.apiLocation + '/subagents', {
			agent: agents.data.id,
			limit: 999
		});

	},

	fetch: function(res) {

		andila.datatable.clear(subagents.datatable);

		for (var data of res.results) {
			
			andila.datatable.populate(subagents.datatable, [
				data.id, data.name, data.phone,
				moment(data.created_at).format('dddd, D MMMM YYYY H:mm:ss'), 
				moment(data.updated_at).format('dddd, D MMMM YYYY H:mm:ss'),
			]);
		}
	},

	fetchFromLocal: function() {

		andila.datatable.clear(subagents.datatable);

		for (var data of agents.data.subagents) {
			
			if (subagents.data.hasOwnProperty(data.id)) {
				continue;
			} else {
				subagents.data[data.id] = data;
			}

			andila.datatable.populate(subagents.datatable, [
				data.id, data.name, data.phone,
				numeral(data.contract_value).format('0,0') + ' Tabung',
			]);
		}
	},

	chartify: function() {

		var chunk = [];
		var labelsForChart = [];
		var dataForChart = {
			backgroundColor: 'rgba(221,75,57, 0.5)',
			label: 'Kuota/Hari',
		};

		for (var data in subagents.data) {
			labelsForChart.push(data);
			chunk.push(subagents.data[data].contract_value);
		}

		dataForChart.data = chunk;

		andila.chart.initializeBar(subagents.chart, subagents.chartCanvas, labelsForChart, [dataForChart]);

	},

};

var schedules = {

	box: $('#box-schedule-data'),
	datatable: $('#andila-schedule-datatable').DataTable(andila.datatable.defaultOptions),
	toggler: $('a[href="#schedules"][data-toggle="tab"]'),
	chartBox: $('#box-schedule-chart'),
	chartCanvas: $('#chart-schedule-chart'),
	mapBox: $('#box-schedule-map'),
	chart: null,
	data: {},

	load: function() {

		return andila.api.get(andila.apiLocation + '/schedules', {
			sort: 'scheduled_date:asc',
			agent: agents.data.id,
			limit: 999,
		});

	},

	fetch: function(res) {

		andila.datatable.clear(schedules.datatable);

		for (var data of res.results) {

			schedules.data[data.id] = data;

			var station = stations.data[data.station_id].name;
			var quantity = data.order === null ? 'Belum Dipesan' : numeral(data.order.quantity).format('0,0') + ' Tabung';
			var orderedAt = data.order === null ? 'Belum Dipesan' : moment(data.order.created_at).format('dddd, D MMMM YYYY H:mm:ss');
			
			andila.datatable.populate(schedules.datatable, [
				data.id, station,
				moment(data.scheduled_date).format('D MMMM YYYY'),
				quantity, orderedAt,
			]);
		}

	},

	loadAndFetch: function(callback) {

		andila.box.loading(schedules.box);

		schedules.load().done([schedules.fetch, function () {
			callback();
		}]).fail(andila.helper.errorify).always(function () {
			andila.box.finish(schedules.box);
		});

	},

	chartify: function() {

		var chunk = [];
		var labelsForChart = [];
		var dataForChart = {
			backgroundColor: 'rgba(60,141,188,0.5)',
			label: 'Penjualan',
		};

		var previousMonth = null;

		// It is so painful to think about this
		// logic so please don't violate :(
		for (var id in schedules.data) {
			
			var scheduledDate = moment(schedules.data[id].scheduled_date);
			var scheduledMonth = scheduledDate.format('MMMM');

			if (scheduledDate.diff(moment(), 'years') > 0 || schedules.data[id].order === null) {
				continue;
			}

			if (previousMonth === scheduledMonth) {
				chunk.push(chunk.pop() + schedules.data[id].order.quantity);
				continue;
			}

			chunk.push(schedules.data[id].order.quantity);
			labelsForChart.push(scheduledMonth);

			previousMonth = scheduledMonth;
		}

		dataForChart.data = chunk;

		andila.chart.initializeBar(schedules.chart, schedules.chartCanvas, labelsForChart, [dataForChart]);

	},

};

var reports = {

	box: $('#box-report-data'),
	datatable: $('#andila-report-datatable').DataTable(andila.datatable.defaultOptions),
	toggler: $('a[href="#reports"][data-toggle="tab"]'),
	data: {},

	load: function() {

		// Get range for this month by default
		// Or else it'll be so slow
		// Oh yeah. Change to week later
		var range = moment().startOf('month').format('YYYY-MM-DD') + '_' + moment().endOf('month').format('YYYY-MM-DD');

		return andila.api.get(andila.apiLocation + '/subschedules', {
			sort: 'scheduled_date:asc',
			agent: agents.data.id,
			range: range,
			limit: 999,
		});

	},

	fetch: function(res) {

		andila.datatable.clear(reports.datatable);

		for (var data of res.results) {

			var subagent = subagents.data[data.subagent_id].name;
			
			reports.data[data.id] = data;

			if (data.report !== null) {
				
				var allocated = numeral(data.report.allocated_qty).format('0,0');

				if (data.report.reported_at !== null) {

					var retailers = 0;

					$.each(data.report.retailers, function(index, retailer) {
						retailers += retailer.pivot.sales_qty;
					});

					var total = numeral(data.report.sales_household_qty + data.report.sales_microbusiness_qty + retailers).format('0,0');
					var reportedAt = moment(data.report.reported_at).format('dddd, D MMMM YYYY H:mm:ss');

				} else {

					var reportedAt = 'Belum Dilaporkan';
					var total = 0;

				}

			} else {
				var allocated = reportedAt = total = 'Belum Ada';
			}

			andila.datatable.populate(reports.datatable, [
				data.id, subagent, 
				moment(data.scheduled_date).format('D MMMM YYYY'),
				allocated, total, reportedAt,
			]);

		}
	},

	loadAndFetch: function(callback) {

		andila.box.loading(reports.box);

		reports.load().done(reports.fetch).fail(andila.helper.errorify).always(function () {
			andila.box.finish(reports.box);
		});

	},

};

$(function(){

	// Load station data to client slide
	agents.load().done(function (res) {
		agents.data = res.model;
	}).fail(andila.helper.errorify);

});

var body = $('body');

// Handle click on mapbox
body.on('click', '#box-profile-map .overlay', function () {

	andila.box.finish(mapBox);
	agents.locate();

});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var target = eval($(this).attr('data-target') + '.datatable');
	andila.datatable.search(target, this.value);

});

// Handle click on station tab toggler
stations.toggler.on('show.bs.tab', function () {

	if (agents.data === null) {
		return false;
	}

	if ($.isEmptyObject(stations.data)) {
		stations.fetchFromLocal();
	}

});

// Handle click on subagent tab toggler
subagents.toggler.on('show.bs.tab', function () {

	if (agents.data === null) {
		return false;
	}

	if ($.isEmptyObject(subagents.data)) {
		subagents.fetchFromLocal();
	}

	subagents.chartify();

});

// Handle click on schedule tab toggler
schedules.toggler.on('show.bs.tab', function () {

	if (agents.data === null) {
		return false;
	}

	if (! $.isEmptyObject(schedules.data)) {
		return;
	}

	if ($.isEmptyObject(stations.data)) {
		stations.fetchFromLocal();
	}

	schedules.loadAndFetch(function() {
		schedules.chartify();
	});

});

// Handle click on report tab toggler
reports.toggler.on('show.bs.tab', function () {

	if (subagents.data === null) {
		return false;
	}

	if (! $.isEmptyObject(reports.data)) {
		return;
	}

	if ($.isEmptyObject(subagents.data)) {
		subagents.fetchFromLocal();
	}

	reports.loadAndFetch();

});

// Handle click on confirm delete button
body.on('click', '#btn-agent-confirm-delete', function () {

	var btn = $(this);

	if (agents.data === null) {
		return false;
	}

	andila.html.btnLoading(btn);

	agents.delete().done(function (res) {

		andila.html.btnSuccess(btn);
		andila.helper.redirect(andila.appLocation + '/agents', {delete: agents.data.name});

	}).fail([andila.helper.errorify, function () {
		andila.html.btnRepeat(btn);
	}]);

});

}(window.jQuery, window, document));