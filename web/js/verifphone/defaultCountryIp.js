    listPays=['us', 'gb', 'jp', 'ca', 'fr'];

    var input = $("#mobile-number");

    input.intlTelInput({
    //autoFormat: true,//??
    //autoHideDialCode: true,//??
    defaultCountry: paysGeoLocISO,//pays de la local
    //nationalMode: true,
    numberType: "MOBILE",//??
    //onlyCountries: ['us', 'gb', 'jp', 'ca', 'cn'], //avoir si on l'applique
    preferredCountries: listPays, //pays des drapeaux selectionnés
    responsiveDropdown: true, //pour éviter qu'il sort de l'input
    utilsScript: utilsScriptUrl//récupère les extention du début de la local ex :: +33 pour la france
    });


    var idNum=document.getElementById('mobile-number');
    var showValid=document.getElementById('testNum');
    if(numValid == true || typeof(numTel) != 'undefined' && numTel != ''){
            //alert(numTel);
            idNum.value         = numTel;
            showValid.className = 'valid';
    }