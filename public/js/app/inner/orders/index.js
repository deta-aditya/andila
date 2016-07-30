(function($, window, document) {

/*
 * Global variables
 */

var modalShow = $('#modal-orders-show');
var modalCreate = $('#modal-orders-create');

var orders = {

	box: $('#andila-orders-box'),
	datatable: $('.andila-datatable').DataTable(andila.datatable.defaultOptions),

	load: function (data) {

		var realData = data || {};

		if (! realData.hasOwnProperty('limit')) {
			realData.limit = 999;
		}

		return andila.api.get(andila.apiLocation + '/schedules', realData);

	},

	single: function (id) {

		data = {
			station: 1,
			agent: 1
		};

		return andila.api.get(andila.apiLocation + '/schedules/' + id, data);

	},

	order: function (id) {

		return andila.api.post(andila.apiLocation + '/schedules/' + id + '/orders', {});

	},

	fetch: function (res) {

		andila.datatable.clear(orders.datatable);

		for (var data of res.results) {

			if (data.order !== null) {
				var allocation = numeral(data.order.quantity).format('0,0');
				var createdAt = moment(data.order.created_at).format('dddd, D MMMM YYYY H:mm:ss');
				var acceptedAt = data.order.accepted_date ? moment(data.order.accepted_date).format('dddd, D MMMM YYYY') : 'Belum Diterima';
			} else {
				var allocation = createdAt = acceptedAt = andila.html.span('Belum Ada', 'text-red').prop('outerHTML');
			}

			andila.datatable.populate(orders.datatable, [
				data.id, moment(data.scheduled_date).format('D MMMM YYYY'), 
				allocation, createdAt, acceptedAt
			]);
		}

	},

};

$(function(){
	
	// Load orders data into datatable
	orders.load().done([orders.fetch, function () {

		andila.box.finish(orders.box);

	}]).fail(andila.helper.errorify);

});

var body = $('body');

// Handle data change on data-table="search-scheduled-date"
body.on('change', '[data-table="search-scheduled-date"]', function () {
	andila.datatable.search(orders.datatable, this.value, 1);
});


// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {
	andila.datatable.search(orders.datatable, this.value);
});

// Handle click on clickable row on orders datatable
body.on('click', 'tr.clickable', function () {

	var tr = $(this);
	var id = tr.find('td:eq(0)').text();
	var scheduledDate = tr.find('td:eq(1)').text();
	var allocation = tr.find('td:eq(2)').text();
	var accepted = tr.find('td:eq(4)').text();
	var data = {};

	if (allocation === 'Belum Ada') {
		modalShow.find('#btn-order-subschedules').hide();
		modalShow.find('#btn-order-create').show().attr('data-id', id);
		allocation = 'Belum Dipesan';
	} else {
		modalShow.find('#btn-order-create').hide();
		modalShow.find('#btn-order-subschedules').show().attr({
			'data-id': id,
			'href': andila.appLocation + '/subschedules?schedule=' + id,
		});

		if (accepted !== 'Belum Diterima') {
			allocation += ' (diterima pada ' + accepted + ')';
		}
	}

	modalShow.find('.orders-id').text('#' + id);
	modalShow.find('.orders-scheduled-date').text(scheduledDate);
	modalShow.find('.orders-allocated').text(allocation);
	modalShow.modal('show');

});

// Handle click on create order button
body.on('click', '#btn-order-create', function () {

	var id = $(this).attr('data-id');

	orders.single(id).done(function (res) {

		// Exactly like the OrderRepository@single.
		// No. I'm not explaining it again.
		var contractValue = res.model.agent.contract_value * moment(res.model.scheduled_date).add(1, 'months').date();

		modalCreate.find('.schedule-station').text(res.model.station.name);
		modalCreate.find('.schedule-agent').text(res.model.agent.name);
		modalCreate.find('.schedule-scheduled-date').text(res.model.scheduled_date);
		modalCreate.find('.schedule-quantity b').text(numeral(contractValue).format('0,0') + ' Tabung');
		modalCreate.find('[type="submit"]').attr('data-id', id);

		modalShow.modal('hide');
		modalCreate.modal('show');

	}).fail(andila.helper.errorify);

	return false;
})

// Handle create order form submition
body.on('submit', '#form-order-create', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var id = submit.attr('data-id');

	andila.html.btnLoading(submit);

	orders.order(id).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Pesanan berhasil! Silahkan tunggu konfirmasi dari pihak stasiun.'));
		andila.html.btnSuccess(submit);
		andila.box.loading(orders.box);

		// Load orders data into datatable
		orders.load().done([orders.fetch, function () {
			andila.box.finish(orders.box);
		}]).fail(andila.helper.errorify);

	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]).always(function () {
		modalCreate.modal('hide');
	});

	return false;
});

}(window.jQuery, window, document));
