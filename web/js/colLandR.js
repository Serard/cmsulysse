/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 13:29
 * To change this template use File | Settings | File Templates.
 */
var countPhare=0;
$(document).ready(function(){    if(countPhare>0){

    }else{
    $( "#feature" ).clone().prependTo( $( ".colonneA #in" ));
    $(".colonneA>#feature>.chg").className="chg card col_1_of_1 span_1_of_1";

    $( "#phare" ).clone().prependTo( $( ".colonneA" ));

    $( "#feature" ).clone().prependTo( $( ".colonneC #in" ));
    $(".colonneC>#new>.chg").className="chg card col_1_of_1 span_1_of_1";
    countPhare++;
    }


});
