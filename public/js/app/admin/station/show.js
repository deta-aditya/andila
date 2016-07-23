var map;
var agenChart;
var geocoder;
var marker;
var mapContainer = document.getElementById('gmap-location');

function initMap()
{
}

function initializeMap(mapObj, ll)
{
	var latLng = ll.slice(1, -1).split(', ');
	var latLngObj = new google.maps.LatLng(parseFloat(latLng[0]), parseFloat(latLng[1]));

	mapObj = new google.maps.Map(mapContainer, {
		center: {lat: -6.9175, lng: 107.6191},
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false,
		streetViewControl: false
	});

	marker = new google.maps.Marker({
		position: latLngObj,
		map: mapObj
	});

	mapObj.panTo(latLngObj);

}

function initializeChart(chart, ctx)
{
	var chartBox = ctx.parents('.box');
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var ajax = $.ajax({
		headers: {'X-CSRF-TOKEN': csrf},
		type: 'GET',
		dataType: 'json',
		url: chartBox.data('url')
	});
	var options = {
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true
				}
			}]
		}
	};

	chartBox.append(
		$('<div>').addClass('overlay').html(
			$('<i>').addClass('fa fa-refresh fa-spin')
		)
	);

	ajax.done(function (res) {

		var labels = [];
		var dataChunkAllocation = [];
		var dataChunkAllocated = [];
		var data = {};

		for (var item of res) {
			labels.push(item.agen_id);
			dataChunkAllocation.push(item.allocation);
			dataChunkAllocated.push(item.allocated);
		}
		
		chartBox.find('.overlay').fadeOut();

		data = {		
			labels: labels,
			datasets: [{
				backgroundColor: 'rgba(221,75,57, 0.5)',
				label: 'Dialokasi',
				data: dataChunkAllocation
			}, {
				backgroundColor: 'rgba(60, 141, 188, 0.5)',
				label: 'Teralokasi',
				data: dataChunkAllocated
			}],
		};

		chart = new Chart(ctx, {
			type: 'bar',
			options: options,
			data: data
		});
	});

	ajax.fail(function (xhr, status, error) {
		showErrorMessage($('.alert-placeholder'), xhr, error);
	});
	
}

function editMonthlyOn()
{
	var table = $('#table-monthly-this-month');
	var unplanned = table.find('tr:not([data-planned])');
	var label = unplanned.find('.label-warning');
	var datepicker = unplanned.find('.plan-date');
	var buttons = $(this).siblings(':not(.hidden)');
	var hiddens = $(this).siblings('.hidden');

	label.addClass('hidden');
	datepicker.removeClass('hidden');
	buttons.addClass('hidden');
	hiddens.removeClass('hidden');
	$(this).addClass('hidden');
}

function editMonthlyOff()
{
	var table = $('#table-monthly-this-month');
	var unplanned = table.find('tr:not([data-planned])');
	var label = unplanned.find('.label-warning');
	var datepicker = unplanned.find('.plan-date');
	var buttons = $(this).siblings(':not(.hidden)');
	var hiddens = $(this).siblings('.hidden');

	label.removeClass('hidden');
	datepicker.addClass('hidden');
	buttons.addClass('hidden');
	hiddens.removeClass('hidden');
	$(this).addClass('hidden');
}

function showSuccessMessage(container)
{
	var alert = $('<div>').addClass('alert alert-success alert-dismissable').append(
				    $('<button>').addClass('close').attr({'type': 'button', 'data-dismiss': 'alert'}).html('&times;'),
				    $('<h4>').append($('<span>').addClass('glyphicon glyphicon-exclamation-sign'), ' Berhasil!'),
				    $('<p>').html('Permintaan Anda berhasil dilaksanakan.')
				);
	var placeholder = container;

	placeholder.append(alert);
}

function showErrorMessage(container, xhr, error)
{
	var alert = $('<div>').addClass('alert alert-danger alert-dismissable').append(
				    $('<button>').addClass('close').attr({'type': 'button', 'data-dismiss': 'alert'}).html('&times;'),
				    $('<h4>').append($('<span>').addClass('glyphicon glyphicon-exclamation-sign'), ' Tunggu Sebentar!'),
				    $('<p>').html('Sistem mendeteksi kesalahan: "'+ error +'" saat memproses permintaan Anda.')
				);
	var placeholder = container;

	if (xhr.status === 400) {
		var items = xhr.responseJSON;
		var ul = $('<ul>');

		for (var item in items.errors) {
			var li = $('<li>').text(item + ': ');
			var innerUl = $('<ul>');

			li.append(innerUl);

			for (var detail of items.errors[item]) {
				innerUl.append($('<li>').text(detail));
			}

			ul.append(li);
		}

		alert.append(ul);
	}

	placeholder.append(alert);
}

(function($, window, document) {

$(function(){});

var body = $('body');
var select2 = body.find('.select2');
var datepicker = body.find('.datepicker');
var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

select2.select2({
	placeholder: 'Pilih Agen'
});

datepicker.datepicker({
	format: 'yyyy-mm-dd',
	language: 'id',
	startDate: date,
	endDate: lastDay,
});

initializeChart(agenChart, $('#agen-chart'));

// Click to show the map
body.on('click', '.gmap-box .overlay', function ()
{
	var latLng = $('#gmap-location').data('latlng');
	initializeMap(map, latLng);
	$(this).remove();
})

// Handle 'Pilih Agen' change value
.on('change', '#form-monthly-create select[name="agen"]', function ()
{
	var quota = $(this).find('option:selected').data('allocation');
	var allocation = $(this).parents('form').find('input[name="allocation"]');

	allocation.val(quota);
})

// Turn on/off "Edit Monthly Plan" mode
.on('click', '#edit-mode-on', editMonthlyOn)
.on('click', '#edit-mode-off', editMonthlyOff)

// Handle form-monthly-create submition
.on('submit', '#form-monthly-create', function ()
{
	var form = $(this);
	var formData = form.serialize();
	var submit = $(this).find('button[type="submit"]');
	var oldSubmitHTML = submit.html();
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var ajax = $.ajax({
		headers: {'X-CSRF-TOKEN': csrf},
		type: 'POST',
		dataType: 'json',
		data: formData,
		url: form.attr('action')
	});

	submit
		.attr('disabled', 'disabled')
		.html('<i class="fa fa-refresh fa-spin"></i> Memproses Permintaan Anda...');

	ajax.done(function (res) 
	{
		showSuccessMessage($('.alert-placeholder-monthly'));

	});

	ajax.fail(function (xhr, status, error)
	{
		showErrorMessage($('.alert-placeholder-monthly'), xhr, error);
	});

	ajax.always(function ()
	{
		submit.removeAttr('disabled');
		submit.html(oldSubmitHTML);
	})

	return false;
})

}(window.jQuery, window, document));
