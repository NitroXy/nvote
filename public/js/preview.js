function render_preview(itm) {
	$.post("/render.md", {'markdown': $(itm).val() }, function(data) {
		$(itm).parents("tr").find(".preview_target").html(data)
	})
}
$(function() {
	$(".preview").keydown(function(event) { render_preview(this) })
	$(".preview").change(function(event) { render_preview(this) })
	$(".preview").change();
})
