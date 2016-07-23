(function($, window, document) {

$(function(){});

var formStoreRole = $('#purple-store-role');

formStoreRole.on('change', '[name="name"]', function()
{
	var slug = $(this).siblings('[name="slug"]');
	var value = $(this).val();

	switch (value) {
		case 'Administrator': slug.val('admin'); break;
		case 'Agen': slug.val('agen'); break;
		case 'Pangkalan': slug.val('pangkalan'); break;
		default: slug.val(''); break;
	}

	return;
});

}(window.jQuery, window, document));
