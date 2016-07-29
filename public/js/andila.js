/*
 * Andila Master API
 * (c) 2016, Deta Aditya
 */

(function($, window, document){
	
window.andila = {};

/*
 * Static properties
 */

andila.version = 0.1;
andila.devUsername = 'super';
andila.devApiToken = '2c1f4144804500c068528a0d73cfaf78';
andila.csrfToken = $('meta[name="csrf-token"]').attr('content');
andila.appLocation = $('meta[name="app-location"]').attr('content');
andila.apiLocation = $('meta[name="api-location"]').attr('content');

/*
 * andila.accessToken and andila.accessExpiry properties
 * Used for accessing API after login
 */

andila.accessToken = $('meta[name="access-token"]').attr('content');
andila.accessExpiry = $('meta[name="access-expiry"]').attr('content');

/*
 * andila.helper object
 * Provides helper functions
 */

andila.helper = {

	/*
	 * andila.helper.ajax
	 * Run an AJAX function
	 */
	ajax: function (url, type, data, headers) {
		var options = {
			contentType: 'application/json',
			type: type,
			data: data || {},
			dataType: 'json',
			url: url,
			headers: headers
		}

		if (data instanceof FormData) {
			options.processData = false;
			options.contentType = false;
		}

		return $.ajax(options);
	},

	/*
	 * andila.helper.scrollToTop
	 * Scroll the page to top
	 */
	scrollToTop: function () {
		$('html, body').stop().animate({scrollTop: 0}, 1000);
		return false;
	},

	/*
	 * andila.helper.scrollOut
	 * Scroll the page to the element
	 */
	scrollOut: function (element) {
		element.animate({scrollTop: element[0].scrollHeight}, 500);
	},

	/*
	 * andila.helper.redirect
	 * Redirect to page with GET/POST method
	 */
	redirect: function (url, data){

		if (! data) {
			window.location.href = url;
			return;
		}

		var redirector = andila.html.form(url, 'POST').addClass('hidden');

		for (dataPiece in data) {
			redirector.append(andila.html.input(dataPiece, 'text', data[dataPiece]));
		}

		redirector.append(andila.html.input('_token', 'text', andila.csrfToken));

		$('body').append(redirector);
		redirector.submit();

	},

	/*
	 * andila.helper.errorify
	 * Default callback function for AJAX error
	 */
	errorify: function (xhr) {

		var placeholder = $('.alert-placeholder');
		var alert = andila.html.alert('danger');

		if (! placeholder) {
			return alert('Terjadi kesalahan: ' + JSON.stringify(xhr.responseText));
		}

		if (xhr.status === 422 || xhr.status === 400) {
			var items = xhr.responseJSON;
			var ul = andila.html.ul();

			for (var item in items.errors) {
				var li = andila.html.li(item + ': ');
				var innerUl = andila.html.ul();

				li.append(innerUl);

				for (var detail of items.errors[item]) {
					innerUl.append(andila.html.li(detail));
				}

				ul.append(li);
			}

			alert.append(ul);
		} else if (xhr.status === 403) {
			alert.append('Anda tidak memiliki hak untuk mengakses fungsi ini.');
		} else if (xhr.status === 500) {
			alert.append('Terjadi kesalahan yang tidak terduga pada sistem. Jika pesan ini muncul secara berkelanjutan silahkan laporkan pada tim kami.');
		}

		placeholder.append(alert);
		andila.helper.scrollToTop();
	}
};

/*
 * andila.api object
 * Provides utilities for requesting data to the Andila API
 */

andila.api = {

	_getHeaders: function () {
		return {
			'X-Andila-Developer-Username': andila.devUsername,
			'X-Andila-Developer-Api-Token': andila.devApiToken,
			'X-Andila-User-Access-Token': andila.accessToken
		};
	},

	_request: function (url, type, data) {
		return andila.helper.ajax(url, type, data, andila.api._getHeaders());
	},

	get: function (url, data) {
		return andila.api._request(url, 'GET', data);
	},

	post: function (url, data) {
		return andila.api._request(url, 'POST', JSON.stringify(data));
	},

	put: function (url, data) {
		return andila.api._request(url, 'PUT', JSON.stringify(data));
	},

	delete: function (url, data) {
		return andila.api._request(url, 'DELETE', data);
	}

}

/*
 * andila.box object
 * Provides utilities for interacting with box element
 */

andila.box = {

	loading: function (box) {
		return box.append(andila.html.div(andila.html.loading(), 'overlay'));
	},

	mapBlocker: function (box) {
		return box.append(andila.html.div(
			andila.html.p('Silahkan pilih <b>Provinsi</b> pada <i>form</i> <b>Alamat</b> terlebih dahulu.')
		, 'overlay'));
	},

	blocker: function (box, content) {
		return box.append(andila.html.div(content, 'overlay'));
	},

	finish: function (box) {
		box.find('.overlay').fadeOut(function () {
			$(this).remove();	
		});
		return box;
	}

}

/*
 * andila.form object
 * Provides utilities for interacting with form element
 */

andila.form = {

	get: function (form, name) {
		return form.find('[name="'+ name +'"]').val();
	},

	has: function (form, name) {
		return andila.form.get(form, name) !== '' && andila.form.get(form, name) !== null ;
	},

	getArray: function (form, delimeter, name) {
		return andila.form.get(form, name).split(delimeter);
	},

	submitBtn: function (form) {
		return form.find('[type="submit"]');
	},

}

/*
 * andila.datatable object
 * Provides utilities for interacting with datatables
 */

andila.datatable = {

	defaultOptions: {

		language: {
			url : "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian-Alternative.json"
		},

		responsive: {
			"details": true
		},

		dom: "<'row'<'col-sm-12'tr>><'box-body row'<'col-sm-5'i><'col-sm-7'p>>",

		createdRow: function (row, data, index) {
			$(row).addClass('clickable');
		}

	},

	minimalOptions: {

		language: {
			url : "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian-Alternative.json"
		},

		responsive: {
			"details": true
		},

		dom: "<'row'<'col-sm-12'tr>>",

		createdRow: function (row, data, index) {
			$(row).addClass('clickable');
		}

	},

	clear: function (datatable) {
		datatable.clear().draw();
	},

	populate: function (datatable, rows) {
		datatable.row.add(rows).draw(false);
		return datatable;
	},

	len: function (datatable, len) {
		datatable.page.len(len).draw(false);
		return datatable;
	},

	search: function (datatable, input, column) {
		if (column) {
			datatable.column(column).search(input).draw(false);
		} else {
			datatable.search(input).draw(false);
		}

		return datatable;
	},

	select: function (datatable, tr) {
		andila.datatable.deselect(datatable);
		tr.addClass('selected');
	},

	deselect: function (datatable) {
		datatable.rows('.selected').nodes().to$().removeClass('selected');
	},

	remove: function (datatable, tr) {
		datatable.row(tr).remove().draw();
	},

}

/*
 * andila.gmap object
 * Provides utilities for interacting with google map
 */

andila.gmap = {

	map: undefined,
	geocoder: undefined,
	container: $('.google-map').get(0),
	marker: null,
	markers: [],
	infoWindows: [],

	defaultCenter: {lat: -6.9175, lng: 107.6191},
	defaultZoom: 15,

	setContainer: function (jQueryObject) {
		andila.gmap.container = jQueryObject.get(0);
	},

	initialize: function (callback) {

		andila.gmap.geocoder = new google.maps.Geocoder();

		andila.gmap.map = new google.maps.Map(andila.gmap.container, {
			center: andila.gmap.defaultCenter,
			zoom: andila.gmap.defaultZoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			streetViewControl: false
		});

		if (callback) {
			return callback();
		}
	},

	panAndMarkTo: function (e) {

		var location = $('[name="location"]');

		andila.gmap.markAt(e.latLng);
		andila.gmap.panTo(e.latLng);

		location.val(e.latLng.lat() + ',' + e.latLng.lng());

	},

	markAndInfo(latLng, infoString) {
		var marker = new google.maps.Marker({
			position: latLng,
			map: andila.gmap.map
		});

		var infoWindow = new google.maps.InfoWindow({
			content: infoString,
		});

		marker.addListener('click', function () {
			infoWindow.open(andila.gmap.map, marker);
		});

		andila.gmap.markers.push(marker);
		andila.gmap.infoWindows.push(infoWindow);
	},

	markAt: function (latLng) {
		if (andila.gmap.marker !== null) {
			andila.gmap.marker.setMap(null);
		}

		andila.gmap.marker = new google.maps.Marker({
			position: latLng,
			map: andila.gmap.map
		});
	},

	panTo: function (latLng) {
		andila.gmap.map.panTo(latLng);
	},

	panAndPaintTo: function (results, status) {

		if (status != google.maps.GeocoderStatus.OK) {
			alert('Mohon maaf. Google Map tidak dapat menemukan wilayah yang dicari. Silahkan untuk mencari di peta secara manual. Status: ' + status);
		} else {
			andila.gmap.map.setCenter(results[0].geometry.location);
		}

	},

	addressQuery: function (query) {
		return andila.gmap.geocoder.geocode({'address': query}, andila.gmap.panAndPaintTo);
	}

};

/*
 * andila.chart object
 * Provides utilities for interacting with chart.js object
 */
andila.chart = {

	defaultOptions: {
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true
				}
			}]
		}
	},

	initializeBar: function (chart, ctx, labels, datasets) {

		var data = {
			labels: labels,
			datasets: datasets,
		};

		chart = new Chart(ctx, {
			type: 'bar',
			options: andila.chart.defaultOptions,
			data: data,
		});

	},

};

/*
 * andila.html object
 * Provides utilities for creating HTML elements using jQuery
 * Neat isn't it?
 */

andila.html = {

	_alertHeadings: function (type) {
		switch (type) {
			case 'success':
				return ' Berhasil!';
			case 'info':
				return ' Informasi';
			case 'warning':
				return ' Peringatan!';
			case 'danger':
				return ' Terjadi Kesalahan.';
			default:
				return '';
		}
	},

	heading: function (level, content, cls) {

		var h = $('<h' + level + '>');

		if (content) {
			h.append(content);
		}

		if (cls) {
			h.addClass(cls);
		}

		return h;

	},

	p: function (content, cls) {

		var p = $('<p>');

		if (content) {
			p.append(content);
		}

		if (cls) {
			p.addClass(cls);
		}

		return p;

	},

	a: function (href, content, cls, id) {

		var a = $('<a>').attr('href', href);

		if (content) {
			a.append(content);
		}

		if (cls) {
			a.addClass(cls);
		}

		if (id) {
			a.attr('id', id);
		}

		return a;

	},

	span: function (content, cls) {

		var span = $('<span>');

		if (content) {
			span.append(content);
		}

		if (cls) {
			span.addClass(cls);
		}

		return span;

	},

	div: function (content, cls) {

		var div = $('<div>');

		if (content) {
			div.append(content);
		}

		if (cls) {
			div.addClass(cls);
		}

		return div;

	},

	ul: function (lis, cls) {

		var ul = $('<ul>');

		if (lis) {
			ul.append(lis);
		}

		if (cls) {
			ul.addClass(cls);
		}

		return ul;

	},

	li: function (content, cls) {

		var li = $('<li>');

		if (content) {
			li.append(content);
		}

		if (cls) {
			li.addClass(cls);
		}

		return li;

	},

	form: function (action, method) {

		var realMethod = method || 'GET';

		return $('<form>').attr({
			'action': action,
			'method': realMethod
		});

	},

	input: function (name, type, value) {

		var realType = type || 'text';
		var realvalue = value || '';

		return $('<input>').attr({
			'name': name,
			'type': realType,
			'value': realvalue
		});

	},

	option: function (value, text, id) {

		var realText = text || value;
		var option = $('<option>').attr('value', value).html(realText);

		if (id) {
			option.attr('data-id', id);
		}

		return option;

	},

	loading: function () {
		return $('<i>').addClass('fa fa-spinner fa-pulse');
	},

	alert: function (type, messages) {

		var realType = type || 'info';
		var realMessages = messages || '';

		return $('<div>').addClass('alert alert-' + realType + ' alert-dismissable').append([
			andila.html.close('alert'),
			andila.html.heading(4, [andila.html.glyphicon('exclamation-sign'), andila.html._alertHeadings(type)]),
			andila.html.p(realMessages)
		]);

	},

	fa: function (what) {
		return $('<i>').addClass('fa fa-' + what);
	},

	glyphicon: function (what) {
		return $('<span>').addClass('glyphicon glyphicon-' + what);
	},

	close: function (dismiss) {

		var close = $('<button>').addClass('close').attr('type', 'button').html('&times;');

		if (dismiss) {
			close.attr('data-dismiss', dismiss);
		}

		return close;

	},

	tr: function (tds, cls, href) {
		
		var tr = $('<tr>');

		if (tds) {
			tr.append(tds);
		}

		if (cls) {
			tr.addClass(cls);
		}

		if (href) {
			tr.attr('data-href', href);
		}

		return tr;

	},

	td: function (content, cls, href) {

		var td = $('<td>');

		if (content) {
			td.append(content);
		}

		if (cls) {
			td.addClass(cls);
		}

		if (href) {
			td.attr('data-href', href);
		}

		return td;

	},

	getSelectedId: function (select) {
		return select.find('option:selected').data('id');
	},

	cleanOptions: function (select) {
		select.find('option[value!=""]').remove();
	},

	selectDefault: function (select) {
		select.find('option[value=""]').attr('selected', 'selected');
	},

	enable: function (input) {
		input.removeAttr('disabled');
	},

	disable: function (input) {
		input.attr('disabled', 'disabled');
	},

	btnLoading: function (btn) {
		andila.html.disable(btn);
		btn.html( andila.html.loading().prop('outerHTML') + ' Tunggu');
	},

	btnSuccess: function (btn) {
		btn.removeClass('btn-default btn-primary btn-warning btn-danger')
			.addClass('btn-success')
			.html( andila.html.fa('check').prop('outerHTML') + ' Sukses');
	},

	btnRepeat: function (btn) {
		andila.html.enable(btn);
		btn.removeClass('btn-success btn-primary btn-warning btn-danger')
			.addClass('btn-default')
			.html( andila.html.fa('refresh').prop('outerHTML') + ' Ulangi');
	},
};

// Load locale moment to ID
moment.locale('id');

}(window.jQuery, window, document));