<script>
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
// #1 checksCookie functie	
function checkCookieAccept() {
	var koek1 = getCookie("catapult-cookie");
	if (koek1 != "") {
		jQuery("#catacookielaw").hide();
		jQuery("#catacookielaw-setting").show("slow");
	} else {
		jQuery("#catacookielaw-setting").hide();
		jQuery("#catacookielaw").show("slow");
		document.getElementById("catacookielaw").style.display = "block";
	}
}	
// #2 checkAnalyticsCookie functie	
function checkAnalyticsCookie() {
	var koek2 = getCookie("cookieControlAnalytics");
	if (koek2 != "nee") {
		jQuery("input#cookie-analytische-accept").prop('checked', true);
		<?php if (get_theme_mod("ga_code") != ""); { ?>
		jQuery.getScript( "https://www.googletagmanager.com/gtag/js?id=<?php echo get_theme_mod("ga_code");?>");
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo get_theme_mod("ga_code");?>');
		<?php } ?>
	}
}
// #3 checkDerdenCookie functie	
function checkDerdenCookie() {
	var koek3 = getCookie("cookieControlDerden");
	if (koek3 != "nee") {
		jQuery("#cookie-derden-accept").prop('checked', true);
	}
}
	
jQuery( document ).ready(function(){
	checkCookieAccept();
	checkAnalyticsCookie();
	checkDerdenCookie();
});
</script>