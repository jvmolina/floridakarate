( function( $ ) {


      var available;
      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };


      function initChangeAddress() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete2')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);

	var map = new google.maps.Map(document.getElementById('map2'), {
          zoom: 15,
          center: {lat: 25.762763163112311, lng: -80.19189245605475}
        });
        var geocoder = new google.maps.Geocoder();
	 
		var bermudaTriangle = new google.maps.Polygon({
		  paths: [
		    new google.maps.LatLng(25.769047, -80.199487),
		    new google.maps.LatLng(25.769279, -80.182879),
		    new google.maps.LatLng(25.74891, -80.201762),	
		  
		    new google.maps.LatLng(25.750997, -80.213993),
		  ]
		});
	bermudaTriangle.setMap(map);
	  var bounds = new google.maps.LatLngBounds();
	  for (var i = 0; i < bermudaTriangle.getPath().getLength(); i++) {
	    bounds.extend(bermudaTriangle.getPath().getAt(i));
	  }
	  
        document.getElementById('changeAddress').addEventListener('click', function() {
		
		
          geocodeAddress(bermudaTriangle, geocoder, map);
	        


        });

      }

     function geocodeAddress(ber, geocoder, resultsMap) {
	
        var address = document.getElementById('autocomplete2').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
			  
		if (google.maps.geometry.poly.containsLocation(results[0].geometry.location, ber)) {
		    autocomplete=$("#autocomplete2").val();
		    idUser=$("#idUser").val();
		    idSubscription=$("#idSubscription").val();
		    var parametros = {
               "autocomplete": autocomplete,
		 "idUser": idUser,
		 "idSubscription": idSubscription,
	        "action": "updateAddress",
		
                };
		    $.ajax({
				 type: 'POST',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros,
				 success: function(data) { 
					//setTimeout(location.href='http://localhost/wordpress/?page_id=374', 1000);
					 }
 			});
		  } else {
		   

		   $("#myModal").modal('show');
		  }

            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
         
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

	   
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
google.maps.event.addDomListener(window, 'load', initChangeAddress);
} )( jQuery );	
 


