(function($, window, document) {

/*
 * Global variables
 */

var mapBox = $('#box-profile-map');

var subagents = {

	data: null,

	load: function() {
		return andila.api.get($('meta[name="resource-uri"]').attr('content'));
	},

	locate: function() {
		andila.gmap.initialize(function () {
			var latLng = subagents.data.location;

			latLng = new google.maps.LatLng(latLng[0], latLng[1]);

			andila.gmap.markAt(latLng);
			andila.gmap.panTo(latLng);
		});
	},

	delete: function() {
		return andila.api.delete(andila.apiLocation + '/subagents/' + subagents.data.id);
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
			subagent: subagents.data.id,
			range: range,
			limit: 999,
		});

	},

	fetch: function(res) {

		andila.datatable.clear(reports.datatable);

		for (var data of res.results) {
			
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
				data.id, moment(data.scheduled_date).format('D MMMM YYYY'),
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
	subagents.load().done(function (res) {
		subagents.data = res.model;
	}).fail(andila.helper.errorify);

});

var body = $('body');

// Handle click on mapbox
body.on('click', '#box-profile-map .overlay', function () {

	andila.box.finish(mapBox);
	subagents.locate();

});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var target = eval($(this).attr('data-target') + '.datatable');
	andila.datatable.search(target, this.value);

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
body.on('click', '#btn-subagent-confirm-delete', function () {

	var btn = $(this);

	if (subagents.data === null) {
		return false;
	}

	andila.html.btnLoading(btn);

	subagents.delete().done(function (res) {

		andila.html.btnSuccess(btn);
		andila.helper.redirect(andila.appLocation + '/subagents', {delete: subagents.data.name});

	}).fail([andila.helper.errorify, function () {
		andila.html.btnRepeat(btn);
	}]);

});

}(window.jQuery, window, document));