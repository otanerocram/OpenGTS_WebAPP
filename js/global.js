var storageKey		= "YWxlc3NhbQ==";
var localAccount 	= "alessamgps";

// Enlace a la plataforma Version PC
var plataformaLink  = "http://104.227.139.157:8080/track/Track?";

// Enlace a la carpeta de banners
var bannerLink  	= "http://104.227.139.157:8080/track/images/banner";

// Llave del API de Google Maps
var googleKey   	= "AIzaSyBEPCRDO0w_ELCdi8JmvdmGF3VbZS6SPqA";

function lg(msg){
    root.console.log("%cLog: " + "%c"+msg, "color:#BF04A5", "color:#3276b1");
};

function doAnimation(codDiv, animation) {
	// Requiere animatecss.com
    $(codDiv).removeClass(function(){
    	return $( this ).prev().attr("class")
    }).addClass('animated '+ animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
    	//toLog("doAnimation: " + codDiv + "," + animation);
    	if (animation === "bounceOut"){
    		$(codDiv).hide();
    	}
    	$(codDiv).removeClass('animated ' + animation);
    });
};

var storageData 	= {
	accountID:          "",
	userID: 			"",
	password: 			"",
	description:        "",
	isActive: 			0,
    groupID:        	"",
};