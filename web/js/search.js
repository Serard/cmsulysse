

/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 14:46
 * To change this template use File | Settings | File Templates.
 */
    $(document).ready(function(){
        var search = {};
        search.attribute = document.getElementById('search');

        search.request = function(data){
            console.log(data);
            $.post( url, data ,function(response) {
            document.getElementById('response').innerHTML = response;
            console.log(response);
            console.log('toto'+url);
        })
            .done(function() {
                console.log( "second success" );
            })
            .fail(function() {
                console.log( "error" );
            })
            .always(function() {
                console.log( "complete" );
            })
            .complete(function() {
                console.log("second complete");
            });
        };

        $(search.attribute).keyup(function() {
            var data={};
            data.search= search.attribute.value;
            search.request(data);
        });

        $('select').change(function(){
            var category = $("select option:selected").text();
            var id = document.getElementById(category).getAttribute("name");
            if (id == "0") {
                location.href=redirUrl;
            } else {
                location.href=redirUrl+'category/'+id;
            }
        })
    });