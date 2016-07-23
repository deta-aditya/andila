(function($, window, document) {

/*
 * Global variables
 */

var mapBox = $('#box-profile-map');

var stations = {

	data: null,

	load: function() {
		return andila.api.get($('meta[name="resource-uri"]').attr('content'), {
			agents: 1,
			schedules: 1,
			limit: 999,
		});
	},

	locate: function() {
		andila.gmap.initialize(function () {
			var latLng = stations.data.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});
	},

	delete: function() {
		return andila.api.delete(andila.apiLocation + '/stations/' + stations.data.id);
	},

};

var agents = {

	box: $('#box-agent-data'),
	datatable: $('#andila-agent-datatable').DataTable(andila.datatable.defaultOptions),
	toggler: $('a[href="#agents"][data-toggle="tab"]'),
	chartBox: $('#box-agent-chart'),
	chartCanvas: $('#chart-agent-chart'),
	mapBox: $('#box-agent-map'),
	chart: null,
	data: {},

	load: function() {

		return andila.api.get(andila.apiLocation + '/agents', {
			station: stations.data.id,
			limit: 999,
		});

	},

	fetch: function(res) {

		andila.datatable.clear(agents.datatable);

		for (var data of res.results) {
			
			andila.datatable.populate(agents.datatable, [
				data.id, data.name, data.phone,
				moment(data.created_at).format('dddd, D MMMM YYYY H:mm:ss'), 
				moment(data.updated_at).format('dddd, D MMMM YYYY H:mm:ss'),
			]);
		}
	},

	fetchFromLocal: function() {

		andila.datatable.clear(agents.datatable);

		for (var data of stations.data.agents) {
			
			if (agents.data.hasOwnProperty(data.id)) {
				continue;
			} else {
				agents.data[data.id] = data;
			}
			
			var contractValue = data.contract_value * moment().daysInMonth();

			andila.datatable.populate(agents.datatable, [
				data.id, data.name, data.phone,
				numeral(contractValue).format('0,0') + ' Tabung',
			]);
		}
	},

	chartify: function() {

		var chunk = [];
		var labelsForChart = [];
		var dataForChart = {
			backgroundColor: 'rgba(221,75,57, 0.5)',
			label: 'Kuota/Bulan',
		};

		for (var data in agents.data) {
			labelsForChart.push(data);
			chunk.push(agents.data[data].contract_value * moment().daysInMonth());
		}

		dataForChart.data = chunk;

		andila.chart.initializeBar(agents.chart, agents.chartCanvas, labelsForChart, [dataForChart]);

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
			station: stations.data.id,
			limit: 999,
		});

	},

	fetch: function(res) {

		andila.datatable.clear(schedules.datatable);

		for (var data of res.results) {

			schedules.data[data.id] = data;

			var agent = agents.data[data.agent_id].name;
			var quantity = data.order === null ? 'Belum Dipesan' : numeral(data.order.quantity).format('0,0') + ' Tabung';
			var orderedAt = data.order === null ? 'Belum Dipesan' : moment(data.order.created_at).format('dddd, D MMMM YYYY H:mm:ss');
			
			andila.datatable.populate(schedules.datatable, [
				data.id, agent,
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

		// It is so painful to thik about this
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

$(function(){

	// Load station data to client slide
	stations.load().done(function (res) {
		stations.data = res.model;
	}).fail(andila.helper.errorify);

});

var body = $('body');

// Handle click on mapbox
body.on('click', '#box-profile-map .overlay', function () {

	andila.box.finish(mapBox);
	stations.locate();

});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var target = eval($(this).attr('data-target') + '.datatable');
	andila.datatable.search(target, this.value);

});

// Handle click on agent tab toggler
agents.toggler.on('show.bs.tab', function () {

	if (stations.data === null) {
		return false;
	}

	if ($.isEmptyObject(agents.data)) {
		agents.fetchFromLocal();
	}

	agents.chartify();

});

// Handle click on schedule tab toggler
schedules.toggler.on('show.bs.tab', function () {

	if (stations.data === null) {
		return false;
	}

	if (! $.isEmptyObject(schedules.data)) {
		return;
	}

	if ($.isEmptyObject(agents.data)) {
		agents.fetchFromLocal();
	}

	schedules.loadAndFetch(function() {
		schedules.chartify();
	});

});

// Handle click on confirm delete button
body.on('click', '#btn-station-confirm-delete', function () {

	var btn = $(this);

	if (stations.data === null) {
		return false;
	}

	andila.html.btnLoading(btn);

	stations.delete().done(function (res) {

		andila.html.btnSuccess(btn);
		andila.helper.redirect(andila.appLocation + '/stations', {delete: stations.data.name});

	}).fail([andila.helper.errorify, function () {
		andila.html.btnRepeat(btn);
	}]);

});

}(window.jQuery, window, document));