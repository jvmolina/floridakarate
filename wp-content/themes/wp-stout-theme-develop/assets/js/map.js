jQuery(document).ready(function($) {


	  
        document.getElementById('submit').addEventListener('click', function() {
	
street_number=$('#street_number').val();
route=$('#route').val();
locality=$('#locality').val();
administrative_area_level_1=$('#administrative_area_level_1').val();


		var parametros = {
                "route" : route,
                "street_number" : street_number,
	        "action"; "saveAddress",
		"locality" : locality,
	        "administrative_area_level_1" : administrative_area_level_1,
                };
		$.ajax({
				 type: 'POST',
				 url: '/wp-admin/admin-ajax.php', 
				 data: parametros,
				 success: function(data) { 
					
					 }
 			});


        });

     
});
 


