(function($, window, document) {

$(function(){});

var dataTables = $('.andila-data-table');

// Autoload DataTable
dataTables.DataTable({

	"language": {
		"url" : "//cdn.datatables.net/plug-ins/1.10.11/i18n/Indonesian-Alternative.json"
	},

	"responsive": {
		"details": true
	},

	"dom": "<'row'<'col-sm-12'tr>>"

});

}(window.jQuery, window, document));
