$('document').ready(function(){
/**
 * Created with JetBrains PhpStorm.
 * User: Grizzly
 * Date: 29/07/15
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 */
/*global variable*/
var villeGeoLoc;
var paysGeoLoc;
var paysGeoLocISO;
var attrMenuHeader='.tag-list .other';//'.menu.header .other';
var beginPos='<span class="icon geoloc"><a><i class="fa fa-map-marker fa-lg"></i></a></span><span class="descriptIcon">';
var endPos='</span>';
var GlobalOpenMenu='';

/*cookie*/
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;

}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";

}
/*position*/
function PositionAttribut(position)
{
    this.latitude=position.coords.latitude;
    this.longitude=position.coords.longitude;
    this.accuracy=position.coords.accuracy;
    this.altitude=position.coords.altitude;
    this.altitudeAccuracy=position.coords.altitudeAccuracy;
    this.heading=position.coords.heading;
    this.speed=position.coords.speed;
    this.timestamp=position.timestamp;
}
function printScreen(){
    this.attr=attrMenuHeader;
    this.content=beginPos+villeGeoLoc+'/'+paysGeoLoc+endPos;
    console.log('test : '+this.attr +' , '+ this.content);
    this.affiche=$(this.attr).html(this.content);
}
function GoogleApi(latitude,longitude)
{

    var dataRequest;

    this.output='json';
    this.urlApi='https://maps.googleapis.com/maps/api/geocode/'+this.output+'?latlng=';
    this.latitude=latitude;
    this.longitude=longitude;
    this.url=this.urlApi+this.latitude+','+this.longitude;
    this.requestJson=function(){
        $.get(this.url, function(data){
            console.log(data);
            for (var i = 0; i < data.results.length; i++)
            {
                for (var z = 0; z< data.results[i].types.length; z++)
                {
                    if(data.results[i].types[z] == 'locality'){

                        for (var y = 0; y < data.results[i].address_components.length;y++)
                        {
                            for (var x = 0; x < data.results[i].address_components[y].types.length;x++){
                                if( data.results[i].address_components[y].types[x] =='locality')
                                {
                                    console.log('#' +i+ '#'+ z  + ' ' + data.results[i].address_components[y].long_name );
                                    setCookie('villeGeoLoc',data.results[i].address_components[y].long_name,3);
                                }
                            }

                        }
                    }
                    if(data.results[i].types[z] == 'country'){
                        for (var y = 0; y < data.results[i].address_components.length;y++)
                        {
                            console.log('#' +i+ '#'+ z  + ' ' + data.results[i].address_components[y].long_name + ': ' + data.results[i].address_components[y].short_name);

                            setCookie('paysGeoLoc',data.results[i].address_components[y].long_name,3);
                            setCookie('paysGeoLocISO',data.results[i].address_components[y].short_name,3);
                        }
                    }
                }
            }
        })
            .success(function(data){
                villeGeoLoc = getCookie('villeGeoLoc');
                paysGeoLoc = getCookie('paysGeoLoc');
                paysGeoLocISO =getCookie('paysGeoLocISO');
            })
            .complete(function(data){
                console.log('complited');
                printScreen();
            });

    };
}

var Geoloc={
    options : {
        enableHighAccuracy  : true,
        timeout             : 5000,
        maximumAge          : 0
    },
    success :function(pos)
    {
        this.attributs = new PositionAttribut(pos);
        this.geoApi = new GoogleApi(this.attributs.latitude, this.attributs.longitude);
        this.geoApi.requestJson();
    },
    error:function(err)
    {
        console.warn('ERROR(' + err.code + '): ' + err.message);
    },
    geoPos:function(c){
        navigator.geolocation.getCurrentPosition(this.success, this.error, this.options);
    }
};
/* init Geo */
var whereAmI=Geoloc;

/*affiche Geo*/
if(navigator.geolocation){
    whereAmI.geoPos();
}

/* icon menu */
function GlobalOpenMenuInit(){
    if(GlobalOpenMenu!='' && x!=GlobalOpenMenu){
        GlobalOpenMenu.className='iconZoom off';
    }
}



    $(attrMenuHeader).click(function(){
        whereAmI.geoPos();
        GlobalOpenMenu.className='iconZoom off';
        GlobalOpenMenu='';
        console.log('ok geoPos again');
    });

    $('.icon a').click(function(){
        x = $(this).parents('.icon').find( "div.iconZoom")[0];

        GlobalOpenMenuInit();

        x.className=(x.className=='iconZoom off')?'iconZoom on':'iconZoom off';
        console.log(x);
        console.log(GlobalOpenMenu );

        GlobalOpenMenu = x;
    })

});