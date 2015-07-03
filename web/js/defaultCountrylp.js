

    var input = $("#fos_user_registration_form_tel");
    var pays='fr';

    /*$('#user_input_autocomplete_address')
        .change(function () {

        });*/
    $('#user_input_autocomplete_address')
        .bind("change keyup", (function () {
        countryLoc();
    }));

    function countryLoc(){
        var address;
        address=document.getElementById('street_number').value +' '+ document.getElementById('route').value +' '+ document.getElementById('locality').value+' '+ document.getElementById('country').value;

        /**
         * recuperation du pays
         **/
        console.log(address);
        if(address.trim()){
            $.get('http://maps.googleapis.com/maps/api/geocode/json', { address: address }, function(dataGeo){
                resultData=[{address_components:[{short_name:''}]}];
                resultData=dataGeo.results;
                console.log(resultData);

                var i=0;
                var ids=5;
                for (var z=0;z<resultData[i].address_components.length; z++)
                {
                    if(resultData[i].address_components[z].types[0]=='country')
                    {
                        pays = resultData[i].address_components[z].short_name.toLowerCase();

                    }
                }

                console.log(pays);
            })
        }else{
            pays = 'fr';

        }
    }



    input.intlTelInput({
        //autoFormat: true,//??
        //autoHideDialCode: true,//??
        defaultCountry: pays,//pays de la local
        //nationalMode: true,
        numberType: "MOBILE",//??
        //onlyCountries: ['us', 'gb', 'jp', 'ca', 'cn'], //avoir si on l'applique
        preferredCountries: ['us', 'gb', 'jp', 'ca', 'fr'], //pays des drapeaux selectionnés
        responsiveDropdown: true, //pour éviter qu'il sort de l'input
        utilsScript: utilsScriptUrl//récupère les extention du début de la local ex :: +33 pour la france
    });

    input.on("invalidkey", function() {
        input.addClass("flash");
        setTimeout(function() {
            input.removeClass("flash");
        }, 100);
    });

    var idNum=document.getElementById('mobile-number');
    var showValid=document.getElementById('testNum');
    if( typeof(numTel) != 'undefined' && numTel != ''){
        //alert(numTel);
        idNum.value         = numTel;
    }