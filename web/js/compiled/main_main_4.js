$('.grid').masonry({
    itemSelector: '.grid-item',
    columnWidth: 200

});

jQuery(function($){

    var blocDiv = $('#grid-item');
    console.log(blocDiv);


    $('#select_categ').click(function(e){
        var cls = $(this).attr("href").replace('#', '');
        console.log(cls);
        console.log();
        blocDiv.find('.grid-item').removeClass('hidden');
        blocDiv.find('.grid-item:not(.'+cls+')').addClass('hidden');
        e.preventDefault();
    });
})