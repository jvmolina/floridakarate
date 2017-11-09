( function( $ ) {

function number_format(number, decimals, decPoint, thousandsSep){
	decimals = decimals || 0;
	number = parseFloat(number);

	if(!decPoint || !thousandsSep){
		decPoint = '.';
		thousandsSep = ',';
	}

	var roundedNumber = Math.round( Math.abs( number ) * ('1e' + decimals) ) + '';
	var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
	var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
	var formattedNumber = "";

	while(numbersString.length > 3){
		formattedNumber += thousandsSep + numbersString.slice(-3)
		numbersString = numbersString.slice(0,-3);
	}

	return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
}



var total=0;

     
$( '.totalprice' ).change(function() {
 var tot = $('#total');
  tot.val(0);
  $('.totalprice').each(function() {
    if($(this).hasClass('totalprice')) {
	//var inclTax=parseFloat($(this).attr('price'))+parseFloat(2);
      tot.val(($(this).is(':checked') ? parseFloat($(this).attr('price')) : 0) + parseFloat(tot.val()));  
    }
    else {
      tot.val(parseFloat(tot.val()) + (isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val())));
    }
  });
  var totalParts = parseFloat(tot.val()).toFixed(2).split('.');
  tot.val('$' + totalParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.' +  (totalParts.length > 1 ? totalParts[1] : '00')); 
			
   });

$( '.calculatePrice' ).change(function() {
 var tot = $('#totalCorp');
 var corSize=$('input:radio[name="corSize"]:checked').val(); 
 var corFrequency=$('input:radio[name="corFrequency"]:checked').val(); 
  //$('#totalCorp').val(0);
  $('.totalpriceCorp').each(function(i, elem) {
   var frecuency= elem.getAttribute('frecuency');
   var price= elem.getAttribute('price');
   var var_id= elem.getAttribute('id');
   var valor=corFrequency+", "+corSize;
    if(frecuency==valor){
       
	tot.val('$' + number_format( price, 2, '.', ',' ) ); 	
	}
  });
 
  
			
   });


		$( ".validCheck" ).change(function(event) { 

                       if ($(this).is(':checked')==true ) {
				//alert("aqui2");
			$( '[name="'+this.id+'"]').prop("disabled", false);
			//$('.totalprice')..prop("disabled", false);
			

			}else{
			
			
			$( '[name="'+this.id+'"]').prop('checked', false);
			$( '[name="'+this.id+'"]').attr("disabled", "disabled");
			
			}
      		});

		
$( '.validCheck' ).change(function() {
  var tot = $('#total');
  tot.val(0);

  $('.totalprice').each(function() {

    if($(this).hasClass('totalprice')) {
	
       tot.val(($(this).is(':checked') ? parseFloat($(this).attr('price')) : 0) + parseFloat(tot.val()));  
    }
    
  });
  var totalParts = parseFloat(tot.val()).toFixed(2).split('.');
  tot.val('$' + totalParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.' +  (totalParts.length > 1 ? totalParts[1] : '00')); 
			
   });
		
	  
        document.getElementById('hugCheckout').addEventListener('click', function() {
                var car=0;
		var error=0;
	var arr=[];
	var arrCar=[];
	
		if ($('.totalprice').is(':checked')==true ) {
			error=0;
		}else{
			error++;
		}

		
		
	if(error > 0){
		
		$('body,html').animate({scrollTop : 400}, 500);
		$('#errLang').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		return false;
		}
		
		
		var arrName=[];
	         $('.totalprice').each(function(i, elem){
		var name= elem.getAttribute('name');
	        var var_id= elem.getAttribute('id');
		
		var price= elem.getAttribute('price');
		var frequency= elem.getAttribute('frecuency');	
		var type= elem.getAttribute('type');	
	 	if($('input[name='+name+']:radio').is(':checked')){
		var product=$('input:radio[name="'+name+'"]:checked').val();
			
			if(arrName.indexOf(name) >= 0){		 
				arrName.push(name);
			
			    $.ajax({
			    url: 'http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'&attribute_type=individual',
			    data: product, 
			    type: 'POST',
			    async: false,
			    success: function (dato) {
			    }
		            });
			    console.log("Juice");
			    car++;
			    console.log("car+"+car);
			}else{

				arrName.push(name);
			     }
		} 
			
			
			
		});
		

	
     	 if(car>0){
	 setTimeout(
			  function() 
			  { window.location.href = 'http://localhost/wordpress/?page_id=2'; }, 500);	
      
	}
 	  });
       




	/* CORPORATE */

	  document.getElementById('hugCheckoutCorp').addEventListener('click', function() {
		
                var car=0;
		var error=0;
		var arr=[];
		var arrCar=[];
		var corSize=$('input:radio[name="corSize"]:checked').val(); 
                var corFrequency=$('input:radio[name="corFrequency"]:checked').val(); 
		
		if ($('input[name="corSize"]:radio').is(':checked')==true && $('input[name="corFrequency"]:radio').is(':checked')==true) {
			
			error=0;
		}else{
			error++;
		}

		
		
	if(error > 0){
		
		$('body,html').animate({scrollTop : 400}, 500);
		$('#errLang').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		return false;
		}
		
		
		var arrName=[];

		$('.totalpriceCorp').each(function(i, elem) {
		   var frecuency= elem.getAttribute('frecuency');
		   var price= elem.getAttribute('price');
		   var var_id= elem.getAttribute('id');
		   var product= elem.getAttribute('value');
		   var valor=corFrequency+", "+corSize;
		    if(frecuency==valor){
		       
			$.ajax({
			    url: 'http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+corFrequency+'&attribute_size='+corSize+'&attribute_type=individual',
			    data: product, 
			    type: 'POST',
			    async: false,
			    success: function (dato) {
			    }
		            });
			    console.log("Juice");
			    car++;
			    console.log("car+"+car); 	
			}
		  });

	        
		

	
     	 if(car>0){
	 setTimeout(
			  function() 
			  { window.location.href = 'http://localhost/wordpress/?page_id=2'; }, 500);	
      
	}
 	  });
     

    
} )( jQuery );	
 



