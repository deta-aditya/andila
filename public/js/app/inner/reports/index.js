(function($, window, document) {

/*
 * Global variables
 */

var modalRetailers = $('#modal-report-retailers');
var retailerFields = $('.retailer-fields');

var reports = {

	box: $('#andila-report-box'),
	datatable: $('.andila-datatable').DataTable(andila.datatable.defaultOptions),

	// This array is for keeping the
	// retailers data that are about to be
	// completed, or saved to the database
	retailers: [],

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

	complete: function (data) {

		return andila.api.post(andila.apiLocation + 'reports/complete', data);

	},

	// Pardon for the spagettiness here
	// I'm running out of time :((
	fetch: function (res) {

		andila.datatable.clear(reports.datatable);

		for (var data of res.results) {

			if (data.report !== null) {

				var allocated = data.report.allocated_qty;

				if (data.report.reported_at !== null) {
					
					var household = data.report.sales_household_qty;
					var microbusiness = data.report.sales_microbusiness_qty;
					var empty = data.report.stock_empty_qty;
					var filled = data.report.stock_filled_qty;
					
					var retailers = 0;

					$.each(data.report.retailers, function(index, retailer) {
						retailers += retailer.pivot.sales_qty;
					});

					var total = household + microbusiness + retailers;
					var reportedAt = moment(data.report.reported_at).format('dddd, D MMMM YYYY H:mm:ss');

				} else {
					var household = andila.html.input(data.report.id + '-sales_household_qty', 'number').addClass('input-sm form-control').css('width', '75px').attr('min', 0).prop('outerHTML');
					var microbusiness = andila.html.input(data.report.id + '-sales_microbusiness_qty', 'number').addClass('input-sm form-control').css('width', '75px').attr('min', 0).prop('outerHTML');
					var empty = andila.html.input(data.report.id + '-stock_empty_qty', 'number').addClass('input-sm form-control').css('width', '75px').attr('min', 0).prop('outerHTML');
					var filled = andila.html.input(data.report.id + '-stock_filled_qty', 'number').addClass('input-sm form-control').css('width', '75px').attr('min', 0).prop('outerHTML');
					var reportedAt = 'Belum Dilaporkan';
					var total = 0;

					var retailers = andila.html.input(data.report.id + '-sales_retailers_qty', 'hidden').prop('outerHTML') + andila.html.a('#modal-report-retailers', 'Tambah').addClass('btn btn-link btn-sm').attr({
						'data-id': data.report.id,
						'data-toggle': 'modal',
					}).prop('outerHTML');
				}				

			} else {
				var allocated = household = microbusiness = retailers = empty = filled = reportedAt = total = 'Belum Ada';
			}

			andila.datatable.populate(reports.datatable, [
				data.id, moment(data.scheduled_date).format('D MMMM YYYY'), allocated, household, microbusiness,
				retailers, empty, filled, total, reportedAt
			]);
		}

	},

	batchComplete: function (data) {

		return andila.api.post(andila.apiLocation + '/reports/complete', data);

	},

	retailerGroup: function (name, sales) {
		return andila.html.div([
			andila.html.div(
				andila.html.input('retailer_name', 'text', name || '').addClass('form-control')
			, 'col-sm-6'),
			andila.html.div(
				andila.html.input('sales_qty', 'number', sales || '').addClass('form-control').attr({min: 0})
			, 'col-sm-4'),
			andila.html.div(
				andila.html.a('#', andila.html.fa('remove'), 'btn btn-danger btn-retailer-remove')
			, 'col-sm-2'),
		], 'row retailer-group');
	},

};

$(function(){
	
	// Load reports data into datatable
	reports.load().done([reports.fetch, function () {

		andila.box.finish(reports.box);

	}]).fail(andila.helper.errorify);

});

var body = $('body');

// Handle data change on data-table="search-scheduled-date"
body.on('change', '[data-table="search-scheduled-date"]', function () {
	andila.datatable.search(reports.datatable, this.value, 1);
});

// Handle keyboard press on data-table="search"
body.on('keyup', '[data-table="search"]', function () {
	andila.datatable.search(reports.datatable, this.value);
});

// Handle data change on sales inputs in datatable
body.on('change', '.andila-datatable [name*="sales"]', function () {

	var tr = $(this).parents('tr');
	var allocated = tr.find('td:eq(2)');
	var totalPlaceholder = tr.find('td:eq(8)');
	var otherSales = tr.find('input[name*="sales"]');
	var totalQty = 0;

	otherSales.each(function () {
		totalQty += parseInt($(this).val()) || 0;
	});

	if (totalQty > parseInt(allocated.text())) {
		totalPlaceholder.addClass('text-red');
	} else {
		totalPlaceholder.removeClass('text-red');
	}

	totalPlaceholder.text(totalQty);

});

// Handle click on clickable row on orders datatable
body.on('click', 'tr.clickable', function () {

	//

});

// Handle click on a button that triggers retailers modal to open
body.on('click', '[href="#modal-report-retailers"]', function () {

	var doneButton = modalRetailers.find('#btn-retailer-done');
	var id = $(this).attr('data-id');

	doneButton.attr('data-id', id);

	if (reports.retailers[id] === undefined) {
		retailerFields.append(reports.retailerGroup());
		return;
	}

	for(var retailer of reports.retailers[id]) {
		retailerFields.append(reports.retailerGroup(retailer.retailer_name, retailer.sales_qty));
	}

});

// Handle click on add retailer button
body.on('click', '#btn-retailer-add', function () {

	retailerFields.append(reports.retailerGroup());

});

// Handle click on remove retailer button
body.on('click', '.btn-retailer-remove', function () {

	var row = $(this).parents('.retailer-group');

	row.remove();
	return false;

});

// Handle click on done retailer button
body.on('click', '#btn-retailer-done', function () {

	var groups = retailerFields.find('.retailer-group');
	var id = $(this).attr('data-id');
	var trigger = reports.box.find('[data-id="'+ id +'"]');
	var hiddenInput = trigger.siblings('[name="'+ id +'-sales_retailers_qty"]');
	var salesQty = 0;

	reports.retailers[id] = [];

	groups.each(function (i, e) {

		var retailerName = $(e).find('[name="retailer_name"]').val();
		var sales = $(e).find('[name="sales_qty"]').val();

		if (retailerName === '' || sales === '') {
			return; // continue
		}

		reports.retailers[id].push({
			retailer_name: retailerName,
			sales_qty: sales,
		});

		salesQty += parseInt(sales);

	});

	trigger.text(salesQty);
	hiddenInput.val(salesQty).change();
	modalRetailers.modal('hide');

});

// Handle something when the retailers modal is close
modalRetailers.on('hidden.bs.modal', function () {
	retailerFields.find('.retailer-group').remove();
});

// Handle complete report form submition
body.on('submit', '#form-report-complete', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var original = submit.clone();
	var data = [];

	var reportIds = reports.box.find('[href="#modal-report-retailers"]');

	reportIds.each(function (i, e) {

		var id = $(e).attr('data-id');
		var salesHousehold = $('[name="'+ id +'-sales_household_qty"]').val();
		var salesMicrobusiness = $('[name="'+ id +'-sales_microbusiness_qty"]').val();
		var stockFilled = $('[name="'+ id +'-stock_filled_qty"]').val();
		var stockEmpty = $('[name="'+ id +'-stock_empty_qty"]').val();
		var salesRetailers = reports.retailers[id];

		if (salesHousehold === '' || salesMicrobusiness === '' || stockFilled === '' || stockEmpty === '') {
			return; // continue;
		}

		data.push({
			report_id: id,
			sales_household_qty: salesHousehold,
			sales_microbusiness_qty: salesMicrobusiness,
			sales_retailers: salesRetailers,
			stock_empty_qty: stockEmpty,
			stock_filled_qty: stockFilled,
		});

	});

	andila.html.btnLoading(submit);

	reports.batchComplete(data).done(function (res) {

		var placeholder = $('.alert-placeholder');

		placeholder.append(andila.html.alert('success', 'Laporan berhasil disimpan!'));
		andila.box.loading(reports.box);

		submit.replaceWith(original);

		// Load reports data into datatable
		reports.load().done([reports.fetch, function () {
			andila.box.finish(reports.box);
		}]).fail(andila.helper.errorify);

	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;

});

}(window.jQuery, window, document));
