jQuery(function($){

    // Set all variables to be used in scope
    var frame,
    metaBox = $('#metabox_url_video'), // Your meta box id here
    addImgLink = metaBox.find('.upload-custom-img'),
    delImgLink = metaBox.find( '.delete-custom-img'),
    imgContainer = metaBox.find( '.custom-img-container-photo'),
    imgIdInput = metaBox.find( '.video-mp4' ),

    frame2
    addImgLink_img = metaBox.find('.upload-custom-img_img'),
    delImgLink_img = metaBox.find( '.delete-custom-img_img'),
    imgContainer_img = metaBox.find( '.custom-img-container-photo_img'),
    imgIdInput_img = metaBox.find( '.video-mp4_img' );

  
    // ADD VIDEO LINK
    addImgLink.on( 'click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame ) {
          frame.open();
          return;
        }

        // Create a new media frame
        frame = wp.media({
          title: 'Select your video (Only mp4 format)',
          library : { type : 'video'},
          button: {
            text: 'Upload'
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on( 'select', function() {

          // Get media attachment details from the frame state
          var attachment = frame.state().get('selection').first().toJSON();


          // Send the attachment URL to our custom image input field.
          imgContainer.append( '<video width="320" height="143"><source src="'+attachment.url+'" type="video/mp4"></video>' );

          // Send the attachment id to our hidden input
          imgIdInput.val( attachment.url );

          // Hide the add image link
          addImgLink.addClass( 'hidden' );

          // Unhide the remove image link
          delImgLink.removeClass( 'hidden' );
        });

        // Finally, open the modal on click
        frame.open();
      });


    // ADD IMAGE LINK
    addImgLink_img.on( 'click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame2 ) {
          frame2.open();
          return;
        }

        // Create a new media frame
        frame2 = wp.media({
          title: 'Select your image.',
          library : { type : 'image'},
          button: {
            text: 'Upload'
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame2.on( 'select', function() {

          // Get media attachment details from the frame state
          var attachment = frame2.state().get('selection').first().toJSON();


          // Send the attachment URL to our custom image input field.
          imgContainer_img.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

          // Send the attachment id to our hidden input
          imgIdInput_img.val( attachment.url );

          // Hide the add image link
          addImgLink_img.addClass( 'hidden' );

          // Unhide the remove image link
          delImgLink_img.removeClass( 'hidden' );
        });

        // Finally, open the modal on click
        frame2.open();
      });
  
  
      // DELETE VIDEO LINK
      delImgLink.on( 'click', function( event ){

        event.preventDefault();

        // Clear out the preview image
        imgContainer.html( '' );

        // Un-hide the add image link
        addImgLink.removeClass( 'hidden' );

        // Hide the delete image link
        delImgLink.addClass( 'hidden' );

        $(".custom-img-container").html( '' );

        // Delete the image id from the hidden input
        imgIdInput.val( '' );

      });


      // DELETE IMAGE LINK
      delImgLink_img.on( 'click', function( event ){

        event.preventDefault();

        // Clear out the preview image
        imgContainer_img.html( '' );

        // Un-hide the add image link
        addImgLink_img.removeClass( 'hidden' );

        // Hide the delete image link
        delImgLink_img.addClass( 'hidden' );

        $(".custom-img-container_img").html( '' );

        // Delete the image id from the hidden input
        imgIdInput_img.val( '' );

      });



});