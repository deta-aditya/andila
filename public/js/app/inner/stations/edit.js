(function($, window, document) {

/*
 * Global variables
 */
var addressBox = $('#andila-station-address');
var mapBox = $('#andila-gmap-box');

/*
 * Load provinces data from the API
 */
function loadProvinces(data) {

	return andila.api.get(andila.apiLocation + '/id/provinces', data);

}

/*
 * Fetch provinces data to its select
 */
function fetchProvinces(res) {

	var select = $('[name="address_province"]');

	for (var data of res.results) {
		select.append(andila.html.option(data.name, data.name, data.id));
	}
}

/*
 * Load regencies data from the API
 */
function loadRegencies(data) {

	return andila.api.get(andila.apiLocation + '/id/regencies', data);

}

/*
 * Fetch regencies data to its select
 */
function fetchRegencies(res) {

	var select = $('[name="address_regency"]');

	for (var data of res.results) {
		select.append(andila.html.option(data.name, data.name, data.id));
	}

}

/*
 * Load districts data from the API
 */
function loadDistricts(data) {

	return andila.api.get(andila.apiLocation + '/id/districts', data);

}

/*
 * Fetch districts data to its select
 */
function fetchDistricts(res) {

	var select = $('[name="address_district"]');

	for (var data of res.results) {
		select.append(andila.html.option(data.name, data.name, data.id));
	}

}

/*
 * Load subdistricts data from the API
 */
function loadSubdistricts(data) {

	return andila.api.get(andila.apiLocation + '/id/subdistricts', data);

}

/*
 * Fetch subdistricts data to its select
 */
function fetchSubdistricts(res) {

	var select = $('[name="address_subdistrict"]');

	for (var data of res.results) {
		select.append(andila.html.option(data.name, data.name, data.id));
	}

}

/*
 * Chain select interface for convinience
 */
function chainSelectInterfaces(type) {

	var select = $('[name="address_'+ type +'"]');
	var utilities = {};

	switch (type) {

		case 'province':
			utilities = {
				next: $('[name="address_regency"]'),
				url: '/id/regencies',
				descendants: $('[name="address_district"], [name="address_subdistrict"]'),
				loader: loadRegencies,
				fetcher: fetchRegencies
			}; break;

		case 'regency':
			utilities = {
				next: $('[name="address_district"]'),
				url: '/id/districts',
				descendants: $('[name="address_subdistrict"]'),
				loader: loadDistricts,
				fetcher: fetchDistricts
			}; break;

		case 'district':
			utilities = {
				next: $('[name="address_subdistrict"]'),
				url: '/id/subdistricts',
				loader: loadSubdistricts,
				fetcher: fetchSubdistricts
			}; break;

		default: 
			return;

	}

	utilities.data = {};
	utilities.data[type] = andila.html.getSelectedId(select);

	andila.box.loading(addressBox);

	utilities.loader(utilities.data).done([function () {

		andila.html.enable(utilities.next);
		andila.html.cleanOptions(utilities.next);
		andila.html.selectDefault(utilities.next);

	}, utilities.fetcher]).fail(andila.helper.errorify).always(function () {

		andila.box.finish(addressBox);

		if (utilities.hasOwnProperty('descendants')) {
			andila.html.selectDefault(utilities.descendants);
			andila.html.disable(utilities.descendants);
		}

	});
}

$(function(){

	// Load provinces data into its select
	loadProvinces().done([fetchProvinces, function () {

		// Remove the overlay in the address box
		andila.box.finish(addressBox);
		andila.html.selectDefault($('[name="address_province"]'));

	}]).fail(andila.helper.errorify);

	// Initialize map and put marker on the map
	andila.gmap.initialize(function () {
		var latLng = $('[name="location"]').attr('placeholder').split(',');

		$.each(latLng, function(index, value) {
			latLng[index] = parseFloat(value);
		});

		latLng = new google.maps.LatLng(latLng[0], latLng[1]);

		andila.gmap.map.addListener('click', andila.gmap.panAndMarkTo);
		andila.gmap.markAt(latLng);
		andila.gmap.panTo(latLng);
	});

});

var body = $('body');

// Handle change on name="address_province"
body.on('change', '[name="address_province"]', function () {
	var province = $(this).val();

	andila.gmap.addressQuery(province);
	chainSelectInterfaces('province');

});

// Handle change on name="address_regency"
body.on('change', '[name="address_regency"]', function () {
	var regency = $(this).val();

	andila.gmap.addressQuery(regency);
	chainSelectInterfaces('regency');

});

// Handle change on name="address_district"
body.on('change', '[name="address_district"]', function () {
	var district = $(this).val();

	andila.gmap.addressQuery(district);
	chainSelectInterfaces('district');

});

// Handle edit station form submition
body.on('submit', '#form-station-edit', function () {

	var form = $(this);
	var submit = andila.form.submitBtn(form);
	var data = {
		address: {}
	};

	if (andila.form.has(form, 'name')) {
		data.name = andila.form.get(form, 'name');
	}

	if (andila.form.has(form, 'phone')) {
		data.phone = andila.form.get(form, 'phone');
	}

	if (andila.form.has(form, 'location')) {
		data.location = andila.form.getArray(form, ',', 'location');
	}

	if (andila.form.has(form, 'type')) {
		data.type = andila.form.get(form, 'type');
	}

	if (andila.form.has(form, 'address_detail')) {
		data.address.detail = andila.form.get(form, 'address_detail');
	}

	if (andila.form.has(form, 'address_subdistrict')) {
		data.address.subdistrict = andila.form.get(form, 'address_subdistrict');
	}

	if (andila.form.has(form, 'address_district')) {
		data.address.district = andila.form.get(form, 'address_district');
	}

	if (andila.form.has(form, 'address_regency')) {
		data.address.regency = andila.form.get(form, 'address_regency');
	}

	if (andila.form.has(form, 'address_province')) {
		data.address.province = andila.form.get(form, 'address_province');
	}

	if (andila.form.has(form, 'address_postal_code')) {
		data.address.postal_code = andila.form.get(form, 'address_postal_code');
	}

	andila.html.btnLoading(submit);

	andila.api.put(andila.apiLocation + '/stations/' + andila.form.get(form, 'id'), data).done(function (res) {
		andila.html.btnSuccess(submit);
		andila.helper.redirect(andila.appLocation + '/stations', {put: res.model.id});
	}).fail([function () {
		andila.html.btnRepeat(submit);
	}, andila.helper.errorify]);

	return false;
});

}(window.jQuery, window, document));
