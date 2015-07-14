function verifPhone(indice){
    var checkVerifPhone=setInterval(function(){
        if(getCookie('paysGeoLocISO')){
            clearInterval(checkVerifPhone);

            pays = getCookie('paysGeoLocISO').toLowerCase();
            listPays = ['us', 'gb', 'jp', 'ca', 'fr'];
            console.log('inltelinput :: pays :: '+pays);

            var input = $("#fos_user_registration_form_tel");

            input.intlTelInput({
                //autoFormat: true,//??
                //autoHideDialCode: true,//??
                defaultCountry: pays,//pays de la local
                //nationalMode: true,
                numberType: "MOBILE",//??
                //onlyCountries: ['us', 'gb', 'jp', 'ca', 'cn'], //avoir si on l'applique
                preferredCountries: listPays, //pays des drapeaux selectionnés
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
        }
    },indice)
}