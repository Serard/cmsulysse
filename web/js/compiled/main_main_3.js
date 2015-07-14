jQuery(function($){
    var masonryDiv = $('#masonry');
    masonryDiv.masonry({
            isAnimated: true,
            itemSelector:'.bloc'
        }
    );
})