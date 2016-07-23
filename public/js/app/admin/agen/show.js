var map;
var pangkalanChart;
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

function seedDailyTable()
{
	var table = $('#table-daily-this-week');
	var unplanned = table.find('td.day:not(:has(.allocation))');

	unplanned.append(
		$('<div>').addClass('label label-warning').text('N/A')
	);
}

function editDailyOn()
{
	var table = $('#table-daily-this-week');
	var unplanned = table.find('td.day:not(:has(.allocation))');
	var label = unplanned.find('.label-warning');
	var buttons = $(this).siblings(':not(.hidden)');
	var hiddens = $(this).siblings('.hidden');

	label.addClass('hidden');
	buttons.addClass('hidden');
	hiddens.removeClass('hidden');
	$(this).addClass('hidden');

	unplanned.each(function () {
		$(this).append(
			$('<input>')
				.attr({type: 'number', name: 'date_planned[' + $(this).parents('tr').data('id') + ']'})
				.css('width', '50px')
		);
	});
}

function editDailyOff()
{
	var table = $('#table-daily-this-week');
	var unplanned = table.find('td.day:not(:has(.allocation))');
	var label = unplanned.find('.label-warning');
	var buttons = $(this).siblings(':not(.hidden)');
	var hiddens = $(this).siblings('.hidden');

	label.removeClass('hidden');
	buttons.addClass('hidden');
	hiddens.removeClass('hidden');
	$(this).addClass('hidden');

	unplanned.find(':input').remove();
}

function showSuccessMessage(container)
{
	var alert = $('<div>').addClass('alert alert-success alert-dismissable').append(
				    $('<button>').addClass('close').attr({'type': 'button', 'data-dismiss': 'alert'}).html('&times;'),
				    $('<h4>').append($('<span>').addClass('glyphicon glyphicon-exclamation-sign'), ' Berhasil!'),
				    $('<p>').html('Permintaan Anda berhasil dilaksanakan.')
				);

	container.append(alert);
}

function showErrorMessage(container, xhr, error)
{
	var alert = $('<div>').addClass('alert alert-danger alert-dismissable').append(
				    $('<button>').addClass('close').attr({'type': 'button', 'data-dismiss': 'alert'}).html('&times;'),
				    $('<h4>').append($('<span>').addClass('glyphicon glyphicon-exclamation-sign'), ' Tunggu Sebentar!'),
				    $('<p>').html('Sistem mendeteksi kesalahan: "'+ error +'" saat memproses permintaan Anda.')
				);

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

	container.append(alert);
}

(function($, window, document) {

$(function(){});

var body = $('body');
var datepicker = body.find('.datepicker');
var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

datepicker.datepicker({
	format: 'yyyy-mm-dd',
	language: 'id',
	startDate: date,
	endDate: lastDay,
});

initializeChart(pangkalanChart, $('#pangkalan-chart'));

seedDailyTable();

// Click to show the map
body.on('click', '.gmap-box .overlay', function ()
{
	var latLng = $('#gmap-location').data('latlng');
	initializeMap(map, latLng);
	$(this).remove();
})

// Turn on/off "Edit Daily Plan" mode
.on('click', '#edit-mode-on', editDailyOn)
.on('click', '#edit-mode-off', editDailyOff)

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
		showSuccessMessage($('.alert-placeholder-distribution'));
	});

	ajax.fail(function (xhr, status, error)
	{
		showErrorMessage($('.alert-placeholder-distribution'), xhr, error);
	});

	ajax.always(function ()
	{
		submit.removeAttr('disabled');
		submit.html(oldSubmitHTML);
	})

	return false;
})

.on('submit', '#form-agen-delete', function ()
{
	var form = $(this);
	var formData = form.serialize();
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var ajax = $.ajax({
		headers: {'X-CSRF-TOKEN': csrf},
		type: 'POST',
		dataType: 'json',
		data: formData,
		url: form.attr('action')
	});

	ajax.done(function (res) 
	{
		window.location.href = form.data('callback');
	});

	ajax.fail(function (xhr, status, error)
	{
		showErrorMessage($('.alert-placeholder'), xhr, error);
	});

	return false;
})

}(window.jQuery, window, document));
