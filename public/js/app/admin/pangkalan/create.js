var map;
var geocoder;
var marker;
var mapContainer = document.getElementById('gmap-choose-location');

function initMap()
{
}

function initializeMap()
{

	geocoder = new google.maps.Geocoder();

	map = new google.maps.Map(mapContainer, {
		center: {lat: -6.9175, lng: 107.6191},
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false,
		streetViewControl: false
	});

	map.addListener('click', panAndMarkTo);
}

function panAndPaintTo(address)
{
	geocoder.geocode({'address': address}, function (results, status) 
	{
		if (status != google.maps.GeocoderStatus.OK) {
			alert('Mohon maaf. Google Map tidak dapat menemukan wilayah yang dicari. Silahkan untuk mencari di peta secara manual. Status: ' + status);
			return;
		}

		map.setCenter(results[0].geometry.location);

	});
}

function panAndMarkTo(e)
{
	var location = document.getElementsByName('location')[0];

	if (marker !== undefined) {
		marker.setMap(null);
	}

	marker = new google.maps.Marker({
		position: e.latLng,
		map: map
	});

	map.panTo(e.latLng);
	location.value = e.latLng.toString();
}

function runAjax(element, me, child, grandchilds)
{
	var id = element.find('option:selected').data('id');
	var next = $('select[name="address_'+ child +'"]');
	var url = element.data('href');
	var data = {};
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var box = element.parents('.box');

	if (id == 'null') {
		next.find('option[value!="null"]').removeAttr('selected');
		next.find('option[value="null"]').attr('selected', 'selected');
		next.attr('disabled', 'disabled');

		if (grandchilds) {
			grandchilds.find('option[value!="null"]').removeAttr('selected');
			grandchilds.find('option[value="null"]').attr('selected', 'selected');
			grandchilds.attr('disabled', 'disabled');
		}

		return;
	}

	data[me] = id;

	var ajax = $.ajax({
		headers: {'X-CSRF-TOKEN': csrf},
		type: 'GET',
		dataType: 'json',
		data: data,
		url: url,
		beforeSend: function () {
			box.append(
				$('<div>').addClass('overlay').html(
					$('<i>').addClass('fa fa-refresh fa-spin')
				)
			);
		}
	});

	if (grandchilds) {
		grandchilds.find('option[value!="null"]').removeAttr('selected');
		grandchilds.find('option[value="null"]').attr('selected', 'selected');
		grandchilds.attr('disabled', 'disabled');
	}

	ajax.done(function (res) {
		putSelectResponse(res, child);

		next.removeAttr('disabled');
		box.find('.overlay').fadeOut();

	});

	ajax.fail(ajaxError);
}

function putSelectResponse(res, to)
{
	var select = $('select[name="address_' + to + '"]');

	select.find('option[value!="null"]').remove();

	for (var object of res) {
		select.append($('<option>').attr({'value': object.name, 'data-id': object.id}).html(object.name));
	}
}

function ajaxError(xhr, status, error)
{
	alert('Ajax gagal: ' + error);
}

function showErrorMessage(xhr, error)
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

	$('.alert-placeholder').append(alert);
}

(function($, window, document) {

$(function(){});

var body = $('body');
var select2 = body.find('.select2');

select2.select2({
	placeholder: 'Pilih Agen'
});

// Load 'region' data after 'province' changed
body.on('change', '[name="address_province"]', function ()
{
	if ($(this).val() != 'null') {
		$('.gmap-box').find('.overlay').remove();
		initializeMap();
		panAndPaintTo($(this).find('option:selected').val());
	} else {
		$('.gmap-box').append(
			$('<div>').addClass('overlay').html(
				$('<p>').html('Silahkan pilih <b>Provinsi</b> pada <i>form</i> <b>Alamat</b> terlebih dahulu.')
			)
		);
	}

	runAjax($(this), 'province', 'region', $('select[name="address_district"], select[name="address_subdistrict"]'));
});

// Load 'district' data after 'region' changed
body.on('change', '[name="address_region"]', function ()
{
	if ($(this).val() != 'null') {
		panAndPaintTo($(this).find('option:selected').val());
	}

	runAjax($(this), 'region', 'district', $('select[name="address_subdistrict"]'));
});

// Load 'subdistrict' data after 'district' changed
body.on('change', '[name="address_district"]', function ()
{
	if ($(this).val() != 'null') {
		panAndPaintTo($(this).find('option:selected').val());
	}

	runAjax($(this), 'district', 'subdistrict', null);
});

// Handle form-pangkalan-create submition
body.on('submit', '#form-pangkalan-create', function ()
{
	var form = $(this);
	var formData = form.serialize() + '&user_email=' + form.find('input[name="user_username"]').val() + '@andila.dist';
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
		window.location.href = res.resource;
	});

	ajax.fail(function (xhr, status, error)
	{
		showErrorMessage(xhr, error);
		submit.removeAttr('disabled');
		submit.html(oldSubmitHTML);
	});

	return false;
});

}(window.jQuery, window, document));
