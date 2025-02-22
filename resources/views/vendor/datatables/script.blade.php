(function(window,$){
	window.LaravelDataTables = window.LaravelDataTables||{};
	window.LaravelDataTables["%1$s"] = $("#%1$s").DataTable(%2$s);
	window.LaravelDataTables["%1$s"].buttons().container()
	.appendTo('#dataTableBuilder_wrapper .col-md-6:eq(0)');
})(window,jQuery);
