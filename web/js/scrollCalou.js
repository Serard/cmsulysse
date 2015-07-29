/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 13:32
 * To change this template use File | Settings | File Templates.
 */

/* scroll header*/
$(function(){
    $('.header-bottom').data('size','big');
});

$(window).scroll(function(){
    if($(document).scrollTop() > 0)
    {
        if($('.header-bottom').data('size') == 'big')
        {
            $('.header-bottom').data('size','small');
            $('.header-bottom').stop().animate({
                height:'120px',
                zIndex: 999,
                top:'0'
            },200);
            $('#c-circle-nav').fadeIn( "slow" );

            $('.tag-list').fadeOut( 100 );
            $('.logo h2').fadeOut( 100 );
            $('.menu').stop().animate({
                marginLeft: '10%'
            },200);
            $('.search').fadeOut("slow");

        }
    }
    else
    {
        if($('.header-bottom').data('size') == 'small')
        {
            $('.header-bottom').data('size','big');
            $('.header-bottom').stop().animate({
                height:'200px',
                zIndex: 999,
                top: '30px'
            },400);
            $('.search').stop().animate({
                width: '50px',
                marginLeft: '0'
            },200);
            $('.search').fadeIn("slow");
            $('.menu').stop().animate({
                marginLeft: '0'
            },200);
            $('#c-circle-nav').fadeOut( "slow" );
            $('.tag-list').fadeIn( "slow" );
            $('h2').fadeIn( "slow" );
        }
    }
});
$('.sh').click(function(){
    $('.search').focus().toggle();
    $('.search').stop().animate({
        width: '120px',
        marginLeft: '200px'
    },200);
});

/* svg */
jQuery('img.svg').each(function(){
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');

    jQuery.get(imgURL, function(data) {
        // Get the SVG tag, ignore the rest
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID to the new SVG
        if(typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if(typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass+' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Replace image with new SVG
        $img.replaceWith($svg);

    }, 'xml');

});
