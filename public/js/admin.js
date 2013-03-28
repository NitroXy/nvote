$(function() {
	$(".cat_status").click(function() {
		$.post('/admin/category_status', {
			what: $(this).data('what'),
			id: $(this).data('id'),
			value: $(this).is(':checked') ? 1 : 0
		});
	});
})
