(function($, window, document) {

$(function(){});

var body = $('body');
var dataTables = body.find('.bs19-data-table');
var datepicker = body.find('.datepicker');
var select2 = body.find('.select2');

// Autoload Select2
select2.select2();

// Autoload DataTable
dataTables.DataTable({

	"language": {
		"url" : "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian-Alternative.json"
	},

	"responsive": {
		"details": true
	}

});

// Autoload Datepicker
datepicker.each(function () {
	$(this).datepicker({
		format: 'yyyy-mm-dd',
		language: 'id'
	});
});

// Handle Change on "date_from"
body.on('change', 'input[name="date_from"]', function () 
{
	var dateFrom = $(this);
	var dateTo = $('input[name="date_to"]');

	dateTo.datepicker('setStartDate', dateFrom.datepicker('getDate'));
})

// Show modal on "#seed" click
.on('click', '#seed', function ()
{
	var modal = $('div#modal-seed');

	modal.modal();
})

// Handle "#seed-apply" on click
.on('click', '#seed-apply', function ()
{
	var btn = $('button#seed');
	var search = $('button#search');
	var form = btn.parents('form');
	var btnOld = btn.html();
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var ajax = $.ajax({
		headers: {'X-CSRF-TOKEN': csrf},
		type: 'POST',
		dataType: 'json',
		data: {'seed': true},
		url: form.attr('action')
	});

	btn.attr('disabled', 'disabled')
		.html('<i class="fa fa-refresh fa-spin"></i>');

	ajax.done(function (res) 
	{
		search.click();
	});

	ajax.fail(function (xhr, status, error)
	{
		showErrorMessage(xhr, error);
	});

	ajax.always(function ()
	{
		submit.removeAttr('disabled');
		submit.html(oldSubmitHTML);
	})

	return false;
})

}(window.jQuery, window, document));
