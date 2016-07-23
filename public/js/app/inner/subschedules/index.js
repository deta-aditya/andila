(function($, window, document) {

/*
 * Global variables
 */
var datepickers = $('.andila-datepicker');
var modalCreate = $('#modal-schedule-create');
var modalCreateTrigger = $('[data-target="#modal-schedule-create"]');
var modalShow = $('#modal-schedule-show');
var btnOrder = modalShow.find('#btn-schedule-accept');
var subagents = {

	box: $('#andila-subagents-box'),
	datatable: $('#andila-subagents-datatable').DataTable(andila.datatable.minimalOptions),
	selected: null,
	
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
			andila.datatable.populate(subagents.datatable, [
				data.id, data.name, data.contract_value,
			]);
		}

	},
};

var subschedules = {

	box: $('#andila-subschedules-box'),
	datatable: $('#andila-subschedules-datatable').DataTable(andila.datatable.defaultOptions),

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		// Get range for this month by default
		// Or else it'll be so slow
		// Oh yeah. Change to week later
		realData.range = moment().startOf('month').format('YYYY-MM-DD') + '_' + moment().endOf('month').format('YYYY-MM-DD');
		realData.sort = 'scheduled_date:asc';

		return andila.api.get(andila.apiLocation + '/subschedules', realData);

	},

	single: function (id) {

		data = {
			station: 1,
			agent: 1
		};

		return andila.api.get(andila.apiLocation + '/subschedules/' + id, data);

	},

	fetch: function (res) {

		andila.datatable.clear(subschedules.datatable);

		for (var data of res.results) {

			if (data.report !== null) {
				var allocation = numeral(data.report.allocated_qty).format('0,0');
				var createdAt = moment(data.report.created_at).format('dddd, D MMMM YYYY H:mm:ss');
				var reportedAt = data.report.reported_at ? moment(data.report.reported_at).format('dddd, D MMMM YYYY H:mm:ss') : 'Belum Dilaporkan';
			} else {
				var allocation = andila.html.input(data.id + '_allocated-qty', 'number').addClass('input-sm form-control').prop('outerHTML');
				var createdAt = reportedAt = andila.html.span('Belum Ada', 'text-red').prop('outerHTML');
			}

			andila.datatable.populate(subschedules.datatable, [
				data.id, moment(data.scheduled_date).format('D MMMM YYYY'), 
				allocation, createdAt, reportedAt
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
	
	// Load subagents data into datatable
	subagents.load().done([subagents.fetch, function () {

		andila.box.finish(subagents.box);

	}]).fail(andila.helper.errorify);

});

var body = $('body');

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {

	var datatable = eval($(this).data('target') + '.datatable');

	andila.datatable.search(datatable, this.value);

});

// Handle click on clickable row on subagents datatable
body.on('click', '#andila-subagents-datatable tr.clickable', function () {

	var tr = $(this);
	var data = {};

	andila.datatable.select(subagents.datatable, tr);

	subagents.selected = {
		id: tr.find('td:eq(0)').text(),
		name: tr.find('td:eq(1)').text(),
	};

	data.subagent = subagents.selected.id;

	andila.box.loading(subschedules.box);

	subschedules.load(data).done([subschedules.fetch, function () {
		andila.box.finish(subschedules.box);
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
				'href': andila.appLocation + '/subschedules?schedule=' + id,
			});

			allocation += ' (diterima pada ' + accepted + ')';
		}
	}

	schedules.single(id).done(function (res) {

		modalShow.find('.schedule-station').text(res.model.station.name);
		modalShow.find('.schedule-agent').text(res.model.agent.name);

		modalShow.find('.schedule-id').text('#' + id);
		modalShow.find('.schedule-scheduled-date').text(res.model.scheduled_date);
		modalShow.find('.schedule-allocated').text(allocation);

		if (res.model.order !== null) {
			modalShow.find('#btn-schedule-accept').attr('data-id', res.model.order.id);
		}

		modalShow.modal('show');

	}).fail([function () {
		modalShow.modal('hide');
	}, andila.helper.errorify]);

});

// Handle create report form submition
body.on('submit', '#form-report-create', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var original = submit.clone();
	var allocatedQtys = form.find('[name$="_allocated-qty"]');
	var data = [];

	if (! allocatedQtys.length) {
		return false;
	}

	allocatedQtys.each(function () {
		var id = $(this).attr('name').split('_')[0];
		var qty = $(this).val();

		if (qty === '') {
			return; // continue;
		}

		data.push({subschedule_id: id, allocated_qty: qty});
	});

	andila.html.btnLoading(submit);

	andila.api.post(andila.apiLocation + '/reports/batch', data).done(function () {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Alokasi telah berhasil disimpan!'));
		andila.html.btnSuccess(submit);

		$('#andila-subagents-datatable tr.selected').click();

		submit.replaceWith(original);

	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;
});

}(window.jQuery, window, document));
