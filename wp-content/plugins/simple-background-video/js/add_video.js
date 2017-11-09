
jQuery(document).ready(function($) {


    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $( 'body' ).addClass( 'movZD' );
    }

	if ($(window).height() > ($(window).width() * 0.562 )) {
        $(".video-home-ZD").css({
            "width": ($(window).height() * 1.78 ),
            "height": ""
        });
        $(".video-home-ZD .video-box").css({
        	"margin-left": $(window).width()-($(window).height() * 1.78 ),
        	"margin-top": ""
        });
    } else {
        $(".video-home-ZD").css({
            "height": ( $(window).width() * 0.562 ),
            "width": ""
        });
        $(".video-home-ZD .video-box").css({
            "margin-top": ($(window).height()-($(window).width() * 0.562))/2,
            "margin-left": ""
		});
    }
    $(".title_video_ZD").css("margin-top", -$(".title_video_ZD").height()/2);


    //Si se esconde video se apaga
    var urlvideo = $(".video-box iframe").attr("src");
    if ($(window).width()<665) {
        $(".video-box iframe").attr("src", "");
        if ( $(".video-home-ZD .video-box video")[0] ) {
        	$("body.uploadZD .video-home-ZD .video-box video").get(0).pause();
        }
    }


    var clicked = 0;

    $('.video-home-ZD .video-box video').click(function(){
        if ( clicked == 1 ) {
            clicked = 0;
        } else {
            clicked = 1;
        }
        
        $(this).get(0).paused ? $(this).get(0).play() : $(this).get(0).pause();
        $('.video-home-ZD .video-box video').css("opacity", ".5");
        setTimeout( function() {
            $('.video-home-ZD .video-box video').css("opacity", "");
        }, 100);
    });

    $(".image_mb").css("height", $(window).height());


    $(window).scroll(function(){
        var scroll=$(window).scrollTop();
        $(".title_video_ZD").css({
            "top": ((scroll*0.05)+50) + "%",
            "opacity": (1-(scroll/600))
        });

        if ($(window).width()>665) {

            if ( scroll>$(".video-home-ZD").height() ) {
                $(".video-box iframe").attr("src", "");
                if ( $(".video-home-ZD .video-box video")[0] ) {
                    $(".video-home-ZD .video-box video").get(0).pause();
                }
            } else {
                if ($(".video-box iframe").attr("src")=="") {
                    $(".video-box iframe").attr("src", urlvideo);
                }
                if ( clicked != 1 ) {
                    if ( $(".video-home-ZD .video-box video")[0] ) {
                        if ( $(".video-home-ZD .video-box video").get(0).paused ) {
                            $(".video-home-ZD .video-box video").get(0).play();
                        }
                    }
                }
            }

        }

    });
	//Cuando se redimensiona la pantalla
    $(window).resize(function(){ 
	//Video Home
        if ($(window).height() > ($(window).width() * 0.562 )) {
            $(".video-home-ZD").css({
                "width": ($(window).height() * 1.78 ), 
                "height": ""
            });
            $(".video-home-ZD .video-box").css({
            	"margin-left": $(window).width()-($(window).height() * 1.78 ),
            	"margin-top": ""
            });
        } else {
            $(".video-home-ZD").css({
                "height": ( $(window).width() * 0.562 ),
                "width": ""
            });
            $(".video-home-ZD .video-box").css({
            	"margin-top": ($(window).height()-($(window).width() * 0.562))/2,
            	"margin-left": ""
            });
        }

        $(".title_video_ZD").css("margin-top", -$(".title_video_ZD").height()/2);
        $(".image_mb").css("height", $(window).height());

        //Si se esconde video se apaga
        if ($(window).width()>665) {
            $(".photo-artist").css("height", $(".box-center").height());
            if ( $(".video-box iframe").attr("src") ) {} else { $(".video-box iframe").attr("src", urlvideo);  }
            if ( $(".video-home-ZD .video-box video")[0] ) {
                if ( scroll>$(".video-home-ZD").height() ) {
                   $(".video-home-ZD .video-box video").get(0).play();
                }
        	}
        } else {
            $(".photo-artist").css("height", "");
            $(".video-box iframe").attr("src", "");
            if ( $(".video-home-ZD .video-box video")[0] ) {
            	$(".video-home-ZD .video-box video").get(0).pause();
            }
            //$(this).get(0).paused ? $(this).get(0).play() : $(this).get(0).pause();
        }

	}); 

});


	
 