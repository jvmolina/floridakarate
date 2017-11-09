( function( $ ) {

	
	document.getElementById('hugSaveAddressUser').addEventListener('click', function() {
		
                 idUser=$('#idUser').val();
		idSubscription=$('#idSubscription').val();
		 idUser=$('#idUser').val();
		sReceiver=$('#sReceiver').val();
		sOtherInfo=$('#sOtherInfo').val();

		var parametros2 = {
                "idUser": idUser,
                "idSubscription" : idSubscription,
		"sReceiver" : sReceiver,
		"sOtherInfo" : sOtherInfo,
 		"action": "SaveAddressUser",
                };
		if(sReceiver!="" || sOtherInfo!=""){
		$.ajax({ 
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros2,
				 success: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').empty();
		$('#errorl').html("<div class='alert alert-success' role='alert'>record saved successfully</div>");
					//setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					//alert("no");
					
					 },
 			});

		}

        });

 	document.getElementById('hugSkipWeek').addEventListener('click', function() {
		
                 idUser=$('#idUser').val();
		idSubscription=$('#idSubscription').val();
		

		var parametros2 = {
                "idUser": idUser,
                "idSubscription" : idSubscription,
 		"action": "changeNextPayment",
                };
		$.ajax({
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros2,
				 success: function(data) { 
					
					//setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					//alert("no");
					
					 },
 			});

		//}

        });
		

	document.getElementById('hugSavePlan').addEventListener('click', function() {
		error=0;
		var arr2 = [];
                $('.changePlan').each(function() {

                       if ($(this).is(':checked')==true ) {
				//alert("aqui2");
			
			
			arr2.push($('input:radio[name="'+this.id+'"]:checked').val());
			

			}else{
			error++;
			}
      		});
              /* if(error>0){
		$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		}else{*/
		idSubscription=$('#idSubscription').val();
		//alert(plan+"---"+idSubscription);

		var parametros2 = {
                "plan": arr2,
                "idSubscription" : idSubscription,
 		"action": "changeProduct",
                };
		$.ajax({
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros2,
				 success: function(data) { 
					
					setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					//alert("no");
					
					 },
 			});

		//}

        });




     
} )( jQuery );	
 
/*

*/

