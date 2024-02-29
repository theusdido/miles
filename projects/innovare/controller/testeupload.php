<?php
if (isset($_GET["tp"])){
	if ($_GET["tp"]=="up"){
		var_dump($_POST);
		exit;	
	}	
}
?>
<input type="file" id="arquivo" name="arquivo" />
<input type="button" id="enviar" value="Enviar" />
<script type="text/javascript" src="system/lib/jquery/jquery.js"></script>
<script type="text/javascript">
$("#enviar").click(function(){
	var data = new FormData();
	jQuery.each(jQuery('#arquivo')[0].files, function(i, file) {
		data.append('file-'+i, file);
	});	
	$.ajax({
		url: 'index.php?controller=testeupload&tp=up',
		data: ,
		cache: false,
		contentType: 'multipart/form-data',
		processData: false,
		type: 'POST',
		success: function(data){
			
		}
	});
});
</script>
