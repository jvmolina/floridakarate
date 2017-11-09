jQuery(document).ready(function($){
    
    $("a.help-video").click(function(){
        $(".help-y-v").toggle();
    });

    $(".video-yvn").change(function(){
        if ($(".video-yvn").val()=="no-video") {
            $(".artist-url_video").attr("value", "");
            $(".video-metabox img").attr("src", "");
            $(".artist-url_video").attr("placeholder", "");
            $(".artist-url_video").css("display", "none");
            $(".selector_video").css("display", "none");
            $(".cuadro-video").css("display", "none");
            $(".help_y").css("display", "none");
            $('.custom-img-container video').css("display", "none");
            $( '.hide-if-no-opt' ).css( 'display', 'none' );
        } else {
            $( '.hide-if-no-opt' ).css( 'display', 'inherit' );
        }
        if ($(".video-yvn").val()=="upload") {
            $(".selector_video").css("display", "inherit");
            $(".artist-url_video").css("display", "none");
            $(".cuadro-video").css("display", "none");
            $(".help_y").css("display", "none");
            $('.custom-img-container video').css("display", "");
            $('.custom-img-container-photo video').css("display", "");
        }
        if ($(".video-yvn").val()=="youtube" || $(".video-yvn").val()=="vimeo" ) {
            $(".selector_video").css("display", "none");
            $(".artist-url_video").css("display", "inherit");
            $(".help_y").css("display", "inherit");
            $('.custom-img-container video').css("display", "none");
            $('.custom-img-container-photo video').css("display", "none");
        }

        $(".artist-url_video").attr("placeholder", "ID of Video..");
    });

    $(".title-ZD").change(function(){
        if ($(".title-ZD").val()=="title-page") {
            $(".custom-title-input").css("display", "none");
            $(".hide_picker").css("display", "inherit");
        }
        if ($(".title-ZD").val()=="custom-title") {
            $(".custom-title-input").css("display", "inherit");
            $(".hide_picker").css("display", "inherit");
        }
        if ($(".title-ZD").val()=="no") {
            $(".custom-title-input").css("display", "none");
            $(".hide_picker").css("display", "none");
        }
    });

    $(".image-sn").change(function(){
        if ($(".image-sn").val()=="yes") {
            $(".selector_image").css("display", "inherit");
            $(".custom-img-container_img").css("display", "inherit");
        } else {
            $(".selector_image").css("display", "none");
            $(".custom-img-container_img").css("display", "none");
        }
    });


    $('.custom-img-container video').click(function(){
        $(this).get(0).paused ? $(this).get(0).play() : $(this).get(0).pause();
        $('.custom-img-container video').css("opacity", ".5");
        setTimeout( function() {
            $('.custom-img-container video').css("opacity", "");
        }, 100);
    });
    $('.custom-img-container-photo').click(function(){
        $('.custom-img-container-photo video').get(0).paused ? $('.custom-img-container-photo video').get(0).play() : $('.custom-img-container-photo video').get(0).pause();
        $('.custom-img-container-photo video').css("opacity", ".5");
        setTimeout( function() {
            $('.custom-img-container-photo video').css("opacity", "");
        }, 100);
    });

    $(".video-yvn").click(function(){
        if ($(".video-yvn").val() != "no-video") {
            $(".artist-url_video").removeAttr("disabled");
        }
    });
    
    $(".help-y-v a").click(function(){
        var urlid = $(this).attr("id");
        $(".help-img").toggle();
        $(".help-img img").attr("src", urlid);
        
    });
    
    $(".back-help, .help-img img").click(function(){
        $(".help-img").css("display", "");
        $(".help-img img").attr("src", "");
    });
    
});   