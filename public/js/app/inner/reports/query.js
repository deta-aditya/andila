(function($, window, document) {

/*
 * Global variables
 */

var user = {

	uri: $('meta[name="this-user-uri"]').attr('content'),
	data: {},

	loadAndPut: function (callback) {
		andila.api.get(user.uri, {
			handleable: 1
		}).done(function (res) {
			
			user.data = res.model;

			if (callback) {
				callback();
			}

		}).fail(andila.helper.errorify);
	},

	isAdmin: function () {
		return user.data.handleable === null;
	},

	isAgent: function () {
		return user.data.handling === 'Agent';
	},

	isSubagent: function () {
		return user.data.handling === 'Subagent';
	},

};

var stations = {

	box: $('#andila-stations-box'),
	datatable: $('#andila-stations-datatable').DataTable(andila.datatable.minimalOptions),
	selected: {},
	data: {},

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		return andila.api.get(andila.apiLocation + '/stations', realData);

	},

	fetch: function (res) {

		andila.datatable.clear(stations.datatable);

		for (var data of res.results) {

			stations.data[data.id] = data;

			andila.datatable.populate(stations.datatable, [
				data.id, data.name,
			]);
		}

	},

	loadAndFetch: function(data, callback) {

		andila.box.loading(stations.box);

		stations.load(data).done([stations.fetch, function () {

			if (callback) {
				callback();
			}

		}]).fail(andila.helper.errorify).always(function () {
			andila.box.finish(stations.box);
		});

	},

	deselect: function () {
		andila.datatable.deselect(stations.datatable);
		stations.selected = {};
		stations.box.find('.is-selected').text('');
	},

};

var agents = {

	box: $('#andila-agents-box'),
	datatable: $('#andila-agents-datatable').DataTable(andila.datatable.minimalOptions),
	selected: {},
	data: {},

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		return andila.api.get(andila.apiLocation + '/agents', realData);

	},

	fetch: function (res) {

		andila.datatable.clear(agents.datatable);

		for (var data of res.results) {

			agents.data[data.id] = data;

			andila.datatable.populate(agents.datatable, [
				data.id, data.name,
			]);
		}

	},

	loadAndFetch: function(data, callback) {

		andila.box.loading(agents.box);

		agents.load(data).done([agents.fetch, function () {

			if (callback) {
				callback();
			}

		}]).fail(andila.helper.errorify).always(function () {
			andila.box.finish(agents.box);
		});

	},

	deselect: function () {
		andila.datatable.deselect(agents.datatable);
		agents.selected = {};
		agents.box.find('.is-selected').text('');
	},

};

var subagents = {

	box: $('#andila-subagents-box'),
	datatable: $('#andila-subagents-datatable').DataTable(andila.datatable.minimalOptions),
	selected: {},
	data: {},

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		return andila.api.get(andila.apiLocation + '/subagents', realData);

	},

	fetch: function (res) {

		andila.datatable.clear(subagents.datatable);

		for (var data of res.results) {

			subagents.data[data.id] = data;

			andila.datatable.populate(subagents.datatable, [
				data.id, data.name,
			]);
		}

	},

	loadAndFetch: function(data, callback) {

		andila.box.loading(subagents.box);

		subagents.load(data).done([subagents.fetch, function () {

			if (callback) {
				callback();
			}

		}]).fail(andila.helper.errorify).always(function () {
			andila.box.finish(subagents.box);
		});

	},

	deselect: function () {
		andila.datatable.deselect(subagents.datatable);
		subagents.selected = {};
		subagents.box.find('.is-selected').text('');
	},

};

var schedules = {

	box: $('#andila-schedules-box'),
	datatable: $('#andila-schedules-datatable').DataTable(andila.datatable.minimalOptions),
	selected: {},
	data: {},

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		realData.ordered = 1;
		realData.sort = 'scheduled_date:asc';

		return andila.api.get(andila.apiLocation + '/schedules', realData);

	},

	fetch: function (res) {

		andila.datatable.clear(schedules.datatable);

		for (var data of res.results) {

			schedules.data[data.id] = data;

			andila.datatable.populate(schedules.datatable, [
				data.id + '/' + data.order.id, moment(data.scheduled_date).format('D MMMM YYYY'),
			]);
		}

	},

	loadAndFetch: function(data, callback) {

		andila.box.loading(schedules.box);

		schedules.load(data).done([schedules.fetch, function () {

			if (callback) {
				callback();
			}

		}]).fail(andila.helper.errorify).always(function () {
			andila.box.finish(schedules.box);
		});

	},

	deselect: function () {
		andila.datatable.deselect(schedules.datatable);
		schedules.selected = {};
		schedules.box.find('.is-selected').text('');
	},

};

$(function(){

	// Autoload Datepicker
	$('.andila-datepicker').each(function () {
		$(this).datepicker({
			format: 'yyyy-mm-dd',
			language: 'id'
		});
	});
	
	user.loadAndPut();

});

var body = $('body');

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var datatable = eval($(this).data('target') + '.datatable');

	andila.datatable.search(datatable, this.value);

});

// Handle click on .clear-selected
body.on('click', '.clear-selected', function () {

	var target = eval($(this).data('target'));

	target.deselect();

	if ($(this).data('target') === 'subagents') {
		target.loadAndFetch();
	}
	
});

// Handle click on clickable row on stations datatable
stations.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var data = {};

	andila.datatable.select(stations.datatable, tr);

	stations.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	data.station = stations.selected.id;

	if (! $.isEmptyObject(agents.selected)) {
		data.agent = agents.selected.id;
	}

	schedules.loadAndFetch(data, function () {

		if (! $.isEmptyObject(schedules.selected)) {
			if (schedules.selected.station_id !== stations.selected.id) {
				schedules.deselect();
			}
		}

	});

	stations.box.find('.is-selected').text(stations.selected.name);
});

// Handle click on clickable row on agents datatable
agents.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var data = {};

	andila.datatable.select(agents.datatable, tr);

	agents.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	data.agent = agents.selected.id;

	andila.box.loading(schedules.box);

	subagents.loadAndFetch(data, function () {

		if (! $.isEmptyObject(subagents.selected)) {
			if (subagents.selected.agent_id !== agents.selected.id) {
				subagents.deselect();
			}
		}

		if (! $.isEmptyObject(stations.selected)) {
			data.station = stations.selected.id;
		}

		schedules.loadAndFetch(data, function () {

			if (! $.isEmptyObject(schedules.selected)) {
				if (schedules.selected.agent_id !== agents.selected.id) {
					schedules.deselect();
				}
			}

		});

	});

	agents.box.find('.is-selected').text(agents.selected.name);
});

// Handle click on clickable row on subagents datatable
subagents.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);

	andila.datatable.select(subagents.datatable, tr);

	subagents.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	subagents.box.find('.is-selected').text(subagents.selected.name);
});

// Handle click on clickable row on schedules datatable
schedules.datatable.on('click', 'tr.clickable', function () {

	var tr = $(this);

	andila.datatable.select(schedules.datatable, tr);

	schedules.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	schedules.box.find('.is-selected').text(schedules.selected.name);
});

// Handle change on range_from field
body.on('change', '[name="range_from"]', function () {

	var from = $(this);
	var to = $('[name="range_to"]');
	var dateFrom = moment(from.val());
	var dateTo = moment(to.val());

	if (! dateFrom.isValid() || ! dateTo.isValid()) {
		return;
	}

	if (from.val() !== '') {
		to.datepicker('setStartDate', dateFrom.toDate());
	}

	if (dateTo.isBefore(dateFrom)) {
		to.val(dateFrom.format('YYYY-MM-DD'));
	}

});

// Handle query report form submition
body.on('submit', '#form-report-query', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var original = submit.clone();
	var data = {};

	if (! $.isEmptyObject(stations.selected)) {
		data.station = stations.selected.id;
	}

	if (! $.isEmptyObject(agents.selected)) {
		data.agent = agents.selected.id;
	}

	if (! $.isEmptyObject(subagents.selected)) {
		data.subagent = subagents.selected.id;
	}

	if (! $.isEmptyObject(schedules.selected)) {
		data.schedule = schedules.selected.id.split('/')[0];
	}

	if (andila.form.has(form, 'range_from') && andila.form.has(form, 'range_to')) {
		data.range = andila.form.get(form, 'range_from') + '_' + andila.form.get(form, 'range_to');
	}

	if ($.isEmptyObject(data)) {
		return false;
	}

	data.reported = 1;

	andila.html.btnLoading(submit);

	var fakeData = $.extend({}, data);
	fakeData.count = 1;

	andila.api.get(andila.apiLocation + '/reports', fakeData).done(function (res) {
		
		if (res.count > 0) {

			andila.html.btnSuccess(submit);
			andila.helper.redirect(andila.appLocation + '/reports/query', data);

		} else {

			var placeholder = $('.alert-placeholder');
			placeholder.append(andila.html.alert('info', 'Tidak ditemukan laporan yang sesuai dengan spesifikasi.'));
			andila.helper.scrollToTop();

			submit.replaceWith(original);

		}

	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;

});

}(window.jQuery, window, document));
