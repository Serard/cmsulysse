/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 13:29
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){
    $( "#feature" ).clone().prependTo( $( ".colonneA #in" ));
    $(".colonneA>#feature>.chg").className="chg card col_1_of_1 span_1_of_1";


    $( "#feature" ).clone().prependTo( $( ".colonneC #in" ));
    $(".colonneC>#new>.chg").className="chg card col_1_of_1 span_1_of_1";
})
