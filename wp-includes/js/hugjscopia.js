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
Juice5days=parseInt($('#Juice5days').val());
Juice3days=parseInt($('#Juice3days').val());
Bowls3days=parseInt($('#Bowls3days').val());
Bowls5days=parseInt($('#Bowls5days').val());
     
		$( "#Juice" ).change(function() {
			
                       if ($('#Juice').is(':checked')==true ) {
			
			$('[name="hugFrequencyJuice"]').prop("disabled", false);
			$( '[name="hugFrequencyJuice"]' ).change(function() {

      			if($( '[name="hugFrequencyJuice"]' ).is(':checked')==true ) {
			
                       if($('input:radio[name=hugFrequencyJuice]:checked').val()==1){
                       
				total=Juice3days;
				total=number_format( total, 2, '.', ',' );
				$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				if($('input:radio[name=hugFrequencyBowls]:checked').val()==1){
				total=Juice3days+Bowls3days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyBowls]:checked').val()==2){
				total=Juice3days+Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}
				
				
			
			}else if($('input:radio[name=hugFrequencyJuice]:checked').val()==2){
				total=Juice5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				if($('input:radio[name=hugFrequencyBowls]:checked').val()==1){
				total=Juice5days+Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyBowls]:checked').val()==2){
			
				total=Juice5days+Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}
			
			}

			
			
			}
			
			
			
      		});

			}else{
			 if ($('#Juice').is(':checked')==false && $('#Bowls').is(':checked')==false) {
			total=0;
			total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
			}

			if($('input:radio[name=hugFrequencyBowls]:checked').val()==1){
				total=Bowls3days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyBowls]:checked').val()==2){
				total=Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}

			$('[name="hugFrequencyJuice"]').prop('checked', false);
			$('[name="hugFrequencyJuice"]').attr("disabled", "disabled");
			}
      		});

		$( "#Bowls" ).change(function() {
			
                       if ($('#Bowls').is(':checked')==true ) {
			
			$('[name="hugFrequencyBowls"]').prop("disabled", false);

			$( '[name="hugFrequencyBowls"]' ).change(function() {
      		
      			 
      		
      			if($( '[name="hugFrequencyBowls"]' ).is(':checked')==true ) {
			
                       if($('input:radio[name=hugFrequencyBowls]:checked').val()==1){
                       
				total=Bowls3days;
				total=number_format( total, 2, '.', ',' );
				$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				if($('input:radio[name=hugFrequencyJuice]:checked').val()==1){
				total=Bowls3days+Juice3days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyJuice]:checked').val()==2){
				total=Bowls3days+Juice5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}
				
				
			
			}else if($('input:radio[name=hugFrequencyBowls]:checked').val()==2){
				total=Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				if($('input:radio[name=hugFrequencyJuice]:checked').val()==1){
				total=Juice3days+Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyJuice]:checked').val()==2){
			
				total=Juice5days+Bowls5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}
			
			}

			
			
			}
			
			
			
      		});


			}else{
			if ($('#Juice').is(':checked')==false && $('#Bowls').is(':checked')==false) {
			total=0;
			total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
			}
			if($('input:radio[name=hugFrequencyJuice]:checked').val()==1){
				total=Juice3days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}else if($('input:radio[name=hugFrequencyJuice]:checked').val()==2){
				total=Juice5days;
				total=number_format( total, 2, '.', ',' );
			$('#total').empty();
			$('#total').html("<label>Total: </label> $"+total);
				}
			$('[name="hugFrequencyBowls"]').prop('checked', false);
			$('[name="hugFrequencyBowls"]').attr("disabled", "disabled");
			}
      		});

		$( "#Both" ).change(function() {
			
                       if ($('#Both').is(':checked')==true ) {
			
			$('[name="Juice"]').prop("checked", false);		
			$('[name="Bowls"]').prop("checked", false);
			$('[name="Juice"]').prop("disabled", true);
			$('[name="Bowls"]').prop("disabled", true);
			$('[name="hugFrequencyBoth"]').prop("disabled", false);
			$('[name="hugFrequencyBowls"]').prop('checked', false);
			$('[name="hugFrequencyBowls"]').attr("disabled", "disabled");
			$('[name="hugFrequencyJuice"]').prop('checked', false);
			$('[name="hugFrequencyJuice"]').attr("disabled", "disabled");
			}else{

			$('[name="Juice"]').prop("disabled", false);
			$('[name="Bowls"]').prop("disabled", false);
			$('[name="hugFrequencyBoth"]').prop('checked', false);
			$('[name="hugFrequencyBoth"]').attr("disabled", "disabled");
			}
      		});
	  
        document.getElementById('hugCheckout').addEventListener('click', function() {
                var car=0;
		var error=0;
	var arr=[];
	var arrCar=[];
	
		if ($('[name="hugFrequencyJuice"]').is(':checked')==true || $('[name="hugFrequencyBowls"]').is(':checked')==true  || $('[name="hugFrequencyBoth"]').is(':checked')==true) {
			error=0;
		}else{
			error++;
		}

		
		
	if(error > 0){
		
		$('body,html').animate({scrollTop : 400}, 500);
		$('#errLang').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		return false;
		}
		
		
		
		if ($('#Juice').is(':checked')==true ) {

		   
			
			if($('input:radio[name=hugFrequencyJuice]:checked').val()==1){
				product=205;	
				var_id=208;
				frequency="3 Days";
			
			} 

			if($('input:radio[name=hugFrequencyJuice]:checked').val()==2){
				product=205;	
				var_id=207;
				frequency="5 Days";
			
			}
		
			arrCar.push('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'');
			 $.get('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'',function(){ });
			alert("TEST : Add to car: Juice");
				console.log("Juice");
			car++;
			console.log("car+"+car);
	}

	if ($('#Bowls').is(':checked')==true ) {

			if($('input:radio[name=hugFrequencyBowls]:checked').val()==1){
				product=302;	
				var_id=305;
				frequency="3 Days";
			
			} 

			if($('input:radio[name=hugFrequencyBowls]:checked').val()==2){
				product=302;	
				var_id=304;
				frequency="5 Days";
			
			}

			arrCar.push('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'');
			 $.get('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'',function(){});
			alert("TEST :Add to car: Bowls");
				console.log("Bowls");
			car++;
			console.log("car+"+car);

	}

	if ($('#Both').is(':checked')==true ) {


			if($('input:radio[name=hugFrequencyBoth]:checked').val()==1){
				product=306;	
				var_id=308;
				frequency="3 Days";
			} 

			if($('input:radio[name=hugFrequencyBoth]:checked').val()==2){
				product=306;	
				var_id=307;
				frequency="5 Days";
			}

			arrCar.push('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'');
			 $.get('http://localhost/wordpress/?add-to-cart='+product+'&variation_id='+var_id+'&attribute_frequency='+frequency+'',function(){});
			alert("Both");
				console.log("Both");
			car++;
			console.log("car+"+car);
	}
	


	
     	 if(car>0){
	 setTimeout(
			  function() 
			  { window.location.href = 'http://localhost/wordpress/?page_id=8'; }, 500);	
      
	}
 	  });
       
function docimage(parametros){
	$.get(parametros,function(){});
				

   }
     

    
} )( jQuery );	
 



