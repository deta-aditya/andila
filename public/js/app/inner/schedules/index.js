(function($, window, document) {

/*
 * Global variables
 */
var datepickers = $('.andila-datepicker');
var modalCreate = $('#modal-schedule-create');
var modalCreateTrigger = $('[data-target="#modal-schedule-create"]');
var modalShow = $('#modal-schedule-show');
var btnOrder = modalShow.find('#btn-schedule-accept');
var stations = {

	box: $('#andila-stations-box'),
	datatable: $('#andila-stations-datatable').DataTable(andila.datatable.minimalOptions),
	selected: null,
	
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
			andila.datatable.populate(stations.datatable, [
				data.id, data.name, data.address.province,
			]);
		}

	},
};

var agents = {

	box: $('#andila-agents-box'),
	datatable: $('#andila-agents-datatable').DataTable(andila.datatable.minimalOptions),
	selected: null,

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		// Agent listed are always the schedulable ones
		realData.schedulable = 1;

		return andila.api.get(andila.apiLocation + '/agents', realData);

	},

	fetch: function (res) {

		andila.datatable.clear(agents.datatable);

		for (var data of res.results) {
			andila.datatable.populate(agents.datatable, [
				data.id, data.name, data.address.province,
			]);
		}

	},

	currentSchedule: function () {
		return andila.api.get(andila.apiLocation + '/agents/' + agents.selected.id + '/schedules', {
			sort: 'scheduled_date:desc',
			limit: 1,
		});
	},

};

var schedules = {

	box: $('#andila-schedules-box'),
	datatable: $('#andila-schedules-datatable').DataTable(andila.datatable.defaultOptions),

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		realData.sort = 'scheduled_date:asc';

		return andila.api.get(andila.apiLocation + '/schedules', realData);

	},

	single: function (id) {

		data = {
			station: 1,
			agent: 1
		};

		return andila.api.get(andila.apiLocation + '/schedules/' + id, data);

	},

	fetch: function (res) {

		andila.datatable.clear(schedules.datatable);

		for (var data of res.results) {

			if (data.order !== null) {
				var allocation = numeral(data.order.quantity).format('0,0');
				var createdAt = moment(data.order.created_at).format('dddd, D MMMM YYYY H:mm:ss');
				var acceptedAt = data.order.accepted_date ? moment(data.order.accepted_date).format('dddd, D MMMM YYYY') : 'Belum Diterima';
			} else {
				var allocation = createdAt = acceptedAt = andila.html.span('Belum Ada', 'text-red').prop('outerHTML');
			}

			andila.datatable.populate(schedules.datatable, [
				data.id, moment(data.scheduled_date).format('D MMMM YYYY'), 
				allocation, createdAt, acceptedAt
			]);
		}

	},

};

$(function(){

	// Autoload Datepicker
	datepickers.each(function () {
		$(this).datepicker({
			format: 'yyyy-mm-dd',
			language: 'id'
		});
	});
	
	// Load stations data into datatable
	stations.load().done([stations.fetch, function () {

		andila.box.finish(stations.box);

	}]).fail(andila.helper.errorify).always(function() {

		// Load agents data into datatable
		// For some reason it has to be loaded synchronously
		// Or else the weblogin session will be deleted
		// I don't know why :(
		agents.load().done([agents.fetch, function () {

			andila.box.finish(agents.box);

		}]).fail(andila.helper.errorify);	

	});

	// Set popover to accept order button
	btnOrder.popover({
		html: true,
		placement: 'bottom',
		content: function () {
			return andila.html.div([
				andila.html.p('Aksi ini dapat memakan waktu yang cukup lama, serta berhati-hatilah karena aksi ini tidak dapat dipertiadakan.'),
				andila.html.div(andila.html.a('#', 'Terima Pesanan', 'btn btn-sm btn-danger', 'btn-schedule-confirm-accept'), 'text-right'),
			]);
		}
	});

});

var body = $('body');

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var datatable = eval($(this).data('target') + '.datatable');

	andila.datatable.search(datatable, this.value);

});

// Handle click on clickable row on stations datatable
body.on('click', '#andila-stations-datatable tr.clickable', function () {

	var tr = $(this);
	var data = {};

	andila.datatable.select(stations.datatable, tr);

	stations.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	data.station = stations.selected.id;

	if (agents.selected !== null) {
		data.agent = agents.selected.id;
		andila.html.enable(modalCreateTrigger);
	}

	andila.box.loading(schedules.box);

	schedules.load(data).done([schedules.fetch, function () {
		andila.box.finish(schedules.box);
	}]).fail(andila.helper.errorify);

});

// Handle click on clickable row on agents datatable
body.on('click', '#andila-agents-datatable tr.clickable', function () {

	var tr = $(this);
	var data = {};

	andila.datatable.select(agents.datatable, tr);

	agents.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	data.agent = agents.selected.id;

	if (stations.selected !== null) {
		data.station = stations.selected.id;
		andila.html.enable(modalCreateTrigger);
	}

	andila.box.loading(schedules.box);

	schedules.load(data).done([schedules.fetch, function () {
		andila.box.finish(schedules.box);
	}]).fail(andila.helper.errorify);

});

// Handle click on clickable row on schedules datatable
body.on('click', '#andila-schedules-datatable tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();
	var allocation = tr.find('td:eq(2)').text();
	var accepted = tr.find('td:eq(4)').text();
	var data = {};

	if (accepted === 'Belum Ada') {
		modalShow.find('#btn-schedule-subschedules').hide();
		modalShow.find('#btn-schedule-accept').hide();
		allocation = 'Belum Dipesan';
	} else {

		if (accepted === 'Belum Diterima') {
			modalShow.find('#btn-schedule-accept').show();
			modalShow.find('#btn-schedule-subschedules').hide();
		} else {
			modalShow.find('#btn-schedule-accept').hide();
			modalShow.find('#btn-schedule-subschedules').show().attr({
				'data-id': id,
			});

			allocation += ' (diterima pada ' + accepted + ')';
		}
	}

	schedules.single(id).done(function (res) {

		modalShow.find('.schedule-station').text(res.model.station.name);
		modalShow.find('.schedule-agent').text(res.model.agent.name);

		modalShow.find('.schedule-id').text('#' + id);
		modalShow.find('.schedule-scheduled-date').text(moment(res.model.scheduled_date).format('D MMMM YYYY'));
		modalShow.find('.schedule-allocated').text(allocation);

		if (res.model.order !== null) {
			modalShow.find('#btn-schedule-accept').attr('data-id', res.model.order.id);
		}

		modalShow.modal('show');

	}).fail([function () {
		modalShow.modal('hide');
	}, andila.helper.errorify]);

});

// Handle show on modal schedule create
modalCreate.on('show.bs.modal', function (e) {

	agents.currentSchedule().done(function (res) {
		
		if (res.count) {

			// Create a month lapse to get the next month thingies. 
			// There's no other way :(
			var scheduled = new Date(res.results[0].scheduled_date);
			var AMonthLapseInUTC = new Date(2000, 1, 1).getTime() - new Date(2000, 0, 1).getTime();
			var nextValid = new Date(scheduled.getTime() + AMonthLapseInUTC);

			datepickers.datepicker('setStartDate', nextValid);

		} else {
			datepickers.datepicker('setStartDate', new Date());
		}

	}).fail([function () {
		modalCreate.modal('hide');
	}, andila.helper.errorify]);

	modalCreate.find('[name="station_id"]').val(stations.selected.id);
	modalCreate.find('[name="agent_id"]').val(agents.selected.id);
	modalCreate.find('.station-name').text(stations.selected.name);
	modalCreate.find('.agent-name').text(agents.selected.name);

});

// Handle create schedule form submition
body.on('submit', '#form-schedule-create', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var data = {
		station_id: andila.form.get(form, 'station_id'),
		agent_id: andila.form.get(form, 'agent_id'),
		scheduled_date: andila.form.get(form, 'scheduled_date'),
	};

	andila.html.btnLoading(submit);

	andila.api.post(andila.apiLocation + '/schedules', data).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Jadwal telah berhasil ditambahkan!'));
		andila.html.btnSuccess(submit);
		andila.box.loading(schedules.box);
		modalCreate.modal('hide');

		schedules.load({
			station: data.station_id,
			agent: data.agent_id,
		}).done([schedules.fetch, function () {
			andila.box.finish(schedules.box);
		}]).fail(andila.helper.errorify);

	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]).always(function () {
		modalCreate.modal('hide');
	});

	return false;
});

// Handle accept order button click
body.on('click', '#btn-schedule-confirm-accept', function () {

	var btn = $(this);
	var id = modalShow.find('#btn-schedule-accept').attr('data-id');

	andila.html.btnLoading(btn);

	andila.api.post(andila.apiLocation + '/orders/' + id + '/accept', {}).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Pesanan telah berhasil diterima!'));
		andila.html.btnSuccess(btn);
		andila.box.loading(schedules.box);

		schedules.load({
			station: res.model.schedule.station_id,
			agent: res.model.schedule.agent_id,
		}).done([schedules.fetch, function () {
			andila.box.finish(schedules.box);
		}]).fail(andila.helper.errorify);

	}).fail([function () {
		andila.html.btnRepeat(btn);
	}, andila.helper.errorify]).always(function () {
		modalShow.modal('hide');
	});

	return false;
});

// Handle see report button click
body.on('click', '#btn-schedule-subschedules', function () {

	var data = {
		schedule: $(this).attr('data-id')
	};

	data.reported = 1;

	var fakeData = $.extend({}, data);
	fakeData.count = 1;

	andila.api.get(andila.apiLocation + '/reports', fakeData).done(function (res) {
		
		if (res.count > 0) {
			andila.helper.redirect(andila.appLocation + '/reports/query', data);
		} else {
			var placeholder = $('.alert-placeholder');
			placeholder.append(andila.html.alert('info', 'Laporan pada jadwal/pesanan ini belum tersedia.'));
			andila.helper.scrollToTop();
		}

	}).fail(andila.helper.errorify).always(function () {
		modalShow.modal('hide');
	});

	return false;

});

}(window.jQuery, window, document));
