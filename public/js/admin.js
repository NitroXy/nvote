$(function() {
	$(".cat_status").change(function() {
		var id = $(this).parents("tr").data('id')
		$.post('/admin/category_status', {
			id: id,
			value: $("input[name='cat_status_" + id + "']:checked" ).val()
		}, function(data) {
			flash_data(data);
		})
	});
})
