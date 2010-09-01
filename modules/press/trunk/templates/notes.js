$(document).ready(function(){ 
	$('a[rel*=facebox]').facebox();
	
	$('input[id=subaddcategory]').click( function() {
		var text = $("input[id=addcategory]").val();
		if ( text != null && text != "" ) {
			$.ajax({
			 type: "POST",
			 url: "/modules/notes/ajax.php?op=addcat",
			 data:   "cat_name=" + text,
			 success: function(msg){
			 	$("span[id=getSelect]").html(msg);
			 	$("input[id=addcategory]").attr("value","");
			 } 
			});	
		} else {
			return false;
		}
	});
});