<?php 
/*Plugin Name: CC Dashboard
Plugin URI: https://www.catapult.nl
Description: Custom dashboard voor Catapult
Version: 2.2.3
Author: Catapult
*/

// UPDATER CODE
require_once('updater.class.php');
if(is_admin()) {
	$updater = new GitHubPluginUpdater(__FILE__, 'catapultcc', "cc-dashboard");
}

// DASHBOARD CODE
function catapult_admin_styles(){
	wp_register_style( 'catapult_admin_stylesheet', plugins_url( '/css/style.css?v=2.1.0', __FILE__ ) );
	wp_enqueue_style( 'catapult_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'catapult_admin_styles' );

function catapult_admin_image () {
	echo '<img src="' . plugins_url( 'images/help.png' , __FILE__ ) . '"><p>Heeft u vragen? Stuur ons een mail op <a href="mailto:communicatie@catapult.nl">communicatie@catapult.nl</a> of bel met 0548-654954</p>';
}
add_filter( 'admin_footer_text', 'catapult_admin_image');


// COOKIEMELDING CODE
if (get_theme_mod("cookie_melding")) {
	// TOEVOEGEN COOKIEMELDING
	add_action( 'wp_footer', 'catapult_cookie_functie' );
	function catapult_cookie_functie() { ?>

		<?php
		// cookies style
		wp_enqueue_style( 'cookies-style', plugins_url('/css/cookies.css?v=2.1.0', __FILE__ ) );
		?>	

		<script type="text/javascript">
		function SetCookie(c_name,value,expiredays)
		{
			var exdate=new Date()
			exdate.setDate(exdate.getDate()+expiredays)
			document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
		}
		</script>
			
		<div id="catacookielaw" style="display: none;">
			<div class="catacookielaw-left" style="letter-spacing: 0.75px;">
				<?php 
				// kijken of WPML gebruikt wordt
				if ( function_exists('icl_object_id') ) {
					// kijken welke taal
					if(ICL_LANGUAGE_CODE=='nl') {
						// kijken of er een alternatieve tekst is ingevuld
						if (get_theme_mod("cookie_tekst") != "") {
							echo "<p>".get_theme_mod("cookie_tekst")."</p>";
						} 
						else {
							echo "<p>Deze website maakt gebruikt van cookies. Accepteer deze cookies voor een optimaal functionerende website of pas deze instellingen hier rechts aan.</p>";
						}
					}
					else {
						if (get_theme_mod("cookie_tekst_en") != "") {
							echo "<p>".get_theme_mod("cookie_tekst_en")."</p>";
						}
						else {
							echo "<p>This website uses cookies to measure user statistics.</p>";
						}
					}
				}
				else {
					if (get_theme_mod("cookie_tekst") != "") {
						echo "<p>".get_theme_mod("cookie_tekst")."</p>";
					} 
					else {
						echo "<p>Deze website maakt gebruikt van cookies. Accepteer deze cookies voor een optimaal functionerende website of pas deze instellingen hier rechts aan.</p>";
					}
				}
				
				?>
				
			</div>
			<div class="catacookielaw-right">
				<?php if (!get_theme_mod("cookie_melding_v2")) { ?><style>a#settingscookie { display: none!IMPORTANT; }</style><?php } ?>
				<a id="settingscookie"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU0IDU0IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1NCA1NDsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiPgo8Zz4KCTxwYXRoIGQ9Ik01MS4yMiwyMWgtNS4wNTJjLTAuODEyLDAtMS40ODEtMC40NDctMS43OTItMS4xOTdzLTAuMTUzLTEuNTQsMC40Mi0yLjExNGwzLjU3Mi0zLjU3MSAgIGMwLjUyNS0wLjUyNSwwLjgxNC0xLjIyNCwwLjgxNC0xLjk2NmMwLTAuNzQzLTAuMjg5LTEuNDQxLTAuODE0LTEuOTY3bC00LjU1My00LjU1M2MtMS4wNS0xLjA1LTIuODgxLTEuMDUyLTMuOTMzLDBsLTMuNTcxLDMuNTcxICAgYy0wLjU3NCwwLjU3My0xLjM2NiwwLjczMy0yLjExNCwwLjQyMUMzMy40NDcsOS4zMTMsMzMsOC42NDQsMzMsNy44MzJWMi43OEMzMywxLjI0NywzMS43NTMsMCwzMC4yMiwwSDIzLjc4ICAgQzIyLjI0NywwLDIxLDEuMjQ3LDIxLDIuNzh2NS4wNTJjMCwwLjgxMi0wLjQ0NywxLjQ4MS0xLjE5NywxLjc5MmMtMC43NDgsMC4zMTMtMS41NCwwLjE1Mi0yLjExNC0wLjQyMWwtMy41NzEtMy41NzEgICBjLTEuMDUyLTEuMDUyLTIuODgzLTEuMDUtMy45MzMsMGwtNC41NTMsNC41NTNjLTAuNTI1LDAuNTI1LTAuODE0LDEuMjI0LTAuODE0LDEuOTY3YzAsMC43NDIsMC4yODksMS40NCwwLjgxNCwxLjk2NmwzLjU3MiwzLjU3MSAgIGMwLjU3MywwLjU3NCwwLjczLDEuMzY0LDAuNDIsMi4xMTRTOC42NDQsMjEsNy44MzIsMjFIMi43OEMxLjI0NywyMSwwLDIyLjI0NywwLDIzLjc4djYuNDM5QzAsMzEuNzUzLDEuMjQ3LDMzLDIuNzgsMzNoNS4wNTIgICBjMC44MTIsMCwxLjQ4MSwwLjQ0NywxLjc5MiwxLjE5N3MwLjE1MywxLjU0LTAuNDIsMi4xMTRsLTMuNTcyLDMuNTcxYy0wLjUyNSwwLjUyNS0wLjgxNCwxLjIyNC0wLjgxNCwxLjk2NiAgIGMwLDAuNzQzLDAuMjg5LDEuNDQxLDAuODE0LDEuOTY3bDQuNTUzLDQuNTUzYzEuMDUxLDEuMDUxLDIuODgxLDEuMDUzLDMuOTMzLDBsMy41NzEtMy41NzJjMC41NzQtMC41NzMsMS4zNjMtMC43MzEsMi4xMTQtMC40MiAgIGMwLjc1LDAuMzExLDEuMTk3LDAuOTgsMS4xOTcsMS43OTJ2NS4wNTJjMCwxLjUzMywxLjI0NywyLjc4LDIuNzgsMi43OGg2LjQzOWMxLjUzMywwLDIuNzgtMS4yNDcsMi43OC0yLjc4di01LjA1MiAgIGMwLTAuODEyLDAuNDQ3LTEuNDgxLDEuMTk3LTEuNzkyYzAuNzUxLTAuMzEyLDEuNTQtMC4xNTMsMi4xMTQsMC40MmwzLjU3MSwzLjU3MmMxLjA1MiwxLjA1MiwyLjg4MywxLjA1LDMuOTMzLDBsNC41NTMtNC41NTMgICBjMC41MjUtMC41MjUsMC44MTQtMS4yMjQsMC44MTQtMS45NjdjMC0wLjc0Mi0wLjI4OS0xLjQ0LTAuODE0LTEuOTY2bC0zLjU3Mi0zLjU3MWMtMC41NzMtMC41NzQtMC43My0xLjM2NC0wLjQyLTIuMTE0ICAgUzQ1LjM1NiwzMyw0Ni4xNjgsMzNoNS4wNTJjMS41MzMsMCwyLjc4LTEuMjQ3LDIuNzgtMi43OFYyMy43OEM1NCwyMi4yNDcsNTIuNzUzLDIxLDUxLjIyLDIxeiBNNTIsMzAuMjIgICBDNTIsMzAuNjUsNTEuNjUsMzEsNTEuMjIsMzFoLTUuMDUyYy0xLjYyNCwwLTMuMDE5LDAuOTMyLTMuNjQsMi40MzJjLTAuNjIyLDEuNS0wLjI5NSwzLjE0NiwwLjg1NCw0LjI5NGwzLjU3MiwzLjU3MSAgIGMwLjMwNSwwLjMwNSwwLjMwNSwwLjgsMCwxLjEwNGwtNC41NTMsNC41NTNjLTAuMzA0LDAuMzA0LTAuNzk5LDAuMzA2LTEuMTA0LDBsLTMuNTcxLTMuNTcyYy0xLjE0OS0xLjE0OS0yLjc5NC0xLjQ3NC00LjI5NC0wLjg1NCAgIGMtMS41LDAuNjIxLTIuNDMyLDIuMDE2LTIuNDMyLDMuNjR2NS4wNTJDMzEsNTEuNjUsMzAuNjUsNTIsMzAuMjIsNTJIMjMuNzhDMjMuMzUsNTIsMjMsNTEuNjUsMjMsNTEuMjJ2LTUuMDUyICAgYzAtMS42MjQtMC45MzItMy4wMTktMi40MzItMy42NGMtMC41MDMtMC4yMDktMS4wMjEtMC4zMTEtMS41MzMtMC4zMTFjLTEuMDE0LDAtMS45OTcsMC40LTIuNzYxLDEuMTY0bC0zLjU3MSwzLjU3MiAgIGMtMC4zMDYsMC4zMDYtMC44MDEsMC4zMDQtMS4xMDQsMGwtNC41NTMtNC41NTNjLTAuMzA1LTAuMzA1LTAuMzA1LTAuOCwwLTEuMTA0bDMuNTcyLTMuNTcxYzEuMTQ4LTEuMTQ4LDEuNDc2LTIuNzk0LDAuODU0LTQuMjk0ICAgQzEwLjg1MSwzMS45MzIsOS40NTYsMzEsNy44MzIsMzFIMi43OEMyLjM1LDMxLDIsMzAuNjUsMiwzMC4yMlYyMy43OEMyLDIzLjM1LDIuMzUsMjMsMi43OCwyM2g1LjA1MiAgIGMxLjYyNCwwLDMuMDE5LTAuOTMyLDMuNjQtMi40MzJjMC42MjItMS41LDAuMjk1LTMuMTQ2LTAuODU0LTQuMjk0bC0zLjU3Mi0zLjU3MWMtMC4zMDUtMC4zMDUtMC4zMDUtMC44LDAtMS4xMDRsNC41NTMtNC41NTMgICBjMC4zMDQtMC4zMDUsMC43OTktMC4zMDUsMS4xMDQsMGwzLjU3MSwzLjU3MWMxLjE0NywxLjE0NywyLjc5MiwxLjQ3Niw0LjI5NCwwLjg1NEMyMi4wNjgsMTAuODUxLDIzLDkuNDU2LDIzLDcuODMyVjIuNzggICBDMjMsMi4zNSwyMy4zNSwyLDIzLjc4LDJoNi40MzlDMzAuNjUsMiwzMSwyLjM1LDMxLDIuNzh2NS4wNTJjMCwxLjYyNCwwLjkzMiwzLjAxOSwyLjQzMiwzLjY0ICAgYzEuNTAyLDAuNjIyLDMuMTQ2LDAuMjk0LDQuMjk0LTAuODU0bDMuNTcxLTMuNTcxYzAuMzA2LTAuMzA1LDAuODAxLTAuMzA1LDEuMTA0LDBsNC41NTMsNC41NTNjMC4zMDUsMC4zMDUsMC4zMDUsMC44LDAsMS4xMDQgICBsLTMuNTcyLDMuNTcxYy0xLjE0OCwxLjE0OC0xLjQ3NiwyLjc5NC0wLjg1NCw0LjI5NGMwLjYyMSwxLjUsMi4wMTYsMi40MzIsMy42NCwyLjQzMmg1LjA1MkM1MS42NSwyMyw1MiwyMy4zNSw1MiwyMy43OFYzMC4yMnoiIGZpbGw9IiMzMzMzMzMiLz4KCTxwYXRoIGQ9Ik0yNywxOGMtNC45NjMsMC05LDQuMDM3LTksOXM0LjAzNyw5LDksOXM5LTQuMDM3LDktOVMzMS45NjMsMTgsMjcsMTh6IE0yNywzNGMtMy44NTksMC03LTMuMTQxLTctN3MzLjE0MS03LDctNyAgIHM3LDMuMTQxLDcsN1MzMC44NTksMzQsMjcsMzR6IiBmaWxsPSIjMzMzMzMzIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></a>
				<a id="removecookie">Ok</a>
			</div>
		</div>

		<!-- Cookie popup -->
		<div id="cookiePopup" class="cmodal">
			<div class="cookiemodal-content">
				<div class="cookie-popup-inner-links">
					<span class="cookie-close cookie-close-mobiel">&times;</span>
					<?php if (get_theme_mod("eigen_logo") != "") { ?>
					<img class="cookie-logo" src="<?php echo esc_url( get_theme_mod( 'eigen_logo' ) ); ?>" alt="<?php bloginfo('name'); ?>" width="200">
					<?php } else { echo "<h1>Cookies</h1>"; } ?>
					<div class="cookietab">
					  	<button class="cookietablinks actiefff" onclick="openCookieTab(event, 'noodzakelijke-cookies-c')">Noodzakelijke cookies</button>
					  	<button class="cookietablinks" onclick="openCookieTab(event, 'analytische-cookies-c')">Analytische cookies</button>
						<?php if (get_theme_mod ('google_tagmanager_code') != "") { ?>
					  	<button class="cookietablinks" onclick="openCookieTab(event, 'externe-cookies-c')">Externe cookies</button>
						<?php } ?>
						<?php if (get_theme_mod ('cookie_privacystatement') != "") { ?>
					  	<button class="cookietablinks" onclick="openCookieTab(event, 'privacy-statement-c')">Privacy statement</button>
						<?php } ?>
					</div>
					<button class="voorkeur-opslaan voorkeur-opslaan-desktop" onClick="cookieVoorkeurOpslaan()">Voorkeur opslaan</button>
				</div>
				<div class="cookie-popup-inner-rechts">
					<p class="kop1">Cookiecontrol</p>
					<p>Geeft je de mogelijkheid om je voorkeur voor cookies aan te passen.</p>
					<span class="cookie-close cookie-close-desktop ">&times;</span>
					<div id="noodzakelijke-cookies-c" class="cookietabcontent"  style="display: block;">
						<p class="kop2">Noodzakelijke cookies</p>
					  	<p>Deze cookies worden gebruikt om de website goed te laten functioneren. Zonder deze cookies kan de website niet gebruikt worden waar hij voor is bedoelt. Het is dan ook niet mogelijk deze uit te schakelen. <br>Als je doorgaat met het gebruik van deze website gaan wij ervan uit dat je dit accepteert.</p> 
						<label class="switch-c">
						  <input type="checkbox" class="checked-grijs" checked disabled>
						  <span class="slider-c round"></span>
						</label>
					</div>
					<div id="analytische-cookies-c" class="cookietabcontent">
					  	<p class="kop2">Analytische cookies</p>
					  	<p>Deze cookies zorgen ervoor dat het mogelijk is om deze website te verbeteren op basis van jouw bezoekgedrag. Zonder deze cookies kunnen wij geen statistieken verzamelen, analyseren en gebruiken om onze website of diensten te verbeteren.</p>
						<label class="switch-c">
						  <input id="cookie-analytische-accept" type="checkbox" onclick="cookiesScriptAnalytische()">
						  <span class="slider-c round"></span>
						</label>
						<script>
						function cookiesScriptAnalytische() {
						  	var checkBox = document.getElementById("cookie-analytische-accept");
							var cookieD1 = new Date();
							cookieD1.setTime(cookieD1.getTime() + (30*24*60*60*1000));
							var expires = "expires="+ cookieD1.toUTCString();
						  	if (checkBox.checked == true){
								console.log("aan");
								document.cookie = 'cookieControlAnalytics' + "=" + 'ja' + ";" + expires + ";path=/";

						  	} else {
								console.log("uit");
								document.cookie = 'cookieControlAnalytics' + "=" + 'nee' + ";" + expires + ";path=/";
						  	}
						}
						</script>
					</div>
					<div id="externe-cookies-c" class="cookietabcontent">
						<p class="kop2">Externe cookies</p>
					  	<p>Deze cookies zorgen ervoor dat eventuele externe partijen jouw bezoek analyseren en gebruiken voor marketing doeleinden. Dit kan bijvoorbeeld Facebook zijn.</p>
						<label class="switch-c">
						 <input id="cookie-derden-accept" type="checkbox" onclick="cookiesScriptDerden()">
						  <span class="slider-c round"></span>
						</label>
						<script>
						function cookiesScriptDerden() {
						  	var checkBox = document.getElementById("cookie-derden-accept");
							var cookieD2 = new Date();
							cookieD2.setTime(cookieD2.getTime() + (30*24*60*60*1000));
							var expires = "expires="+ cookieD2.toUTCString();
						  	if (checkBox.checked == true){
								console.log("aan");
								document.cookie = 'CookieTagDerden' + "=" + 'True' + ";" + expires + ";path=/";
						  	} else {
								console.log("uit");
								document.cookie = 'CookieTagDerden' + "=" + 'False' + ";" + expires + ";path=/";
						  	}
						}
						</script>
					</div>
					<?php if (get_theme_mod ('cookie_privacystatement') != "") { ?>
					<div id="privacy-statement-c" class="cookietabcontent">
						<p class="kop2">Privacy statement</p>
					  	<p>In onze privacy statement vind je informatie over welke gegevens deze website gebruikt, hoe deze worden gebruikt en wat wij eraan doen om deze veilig te houden.</p>
						<a style="color: #2196F3;" href="<?php echo get_theme_mod ('cookie_privacystatement'); ?>" target="_blank">Bekijk hier de privacy statement</a>
					</div>
					<?php } ?>
					<button class="voorkeur-opslaan voorkeur-opslaan-mobiel" onClick="window.location.reload()">Voorkeur opslaan</button>
				</div>
				
		  	</div>
			
		</div>

		<script type="text/javascript">
		// cookie popup
		var cmodal = document.getElementById('cookiePopup');
		var btnC = document.getElementById("settingscookie"); 
		var spanC1 = document.getElementsByClassName("cookie-close-desktop")[0];
		var spanC2 = document.getElementsByClassName("cookie-close-mobiel")[0];
		btnC.onclick = function() {
			cmodal.style.display = "block";
		}
		spanC1.onclick = function() {
			cmodal.style.display = "none";
		}
		spanC2.onclick = function() {
			cmodal.style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == cmodal) {
				cmodal.style.display = "none";
			}
		}
		
		// cookie popup tabs
		function openCookieTab(evt, cityName) {
			var itab , cookietabcontent, cookietablinks;
			cookietabcontent = document.getElementsByClassName("cookietabcontent");
			for (itab = 0; itab < cookietabcontent.length; itab++) {
				cookietabcontent[itab].style.display = "none";
			}
			cookietablinks = document.getElementsByClassName("cookietablinks");
			for (itab = 0; itab < cookietablinks.length; itab++) {
				cookietablinks[itab].className = cookietablinks[itab].className.replace(" actiefff", "");
			}
			document.getElementById(cityName).style.display = "block";
			document.getElementById(cityName).style.animation = "pulse 1s ease";
			evt.currentTarget.className += " actiefff";
		}
			
		// cookie voorkeuren opslaan
		function cookieVoorkeurOpslaan() {
			SetCookie('catapult-cookie','catapult-cookie',60);
			jQuery("#catacookielaw").fadeOut("slow");
			location.reload();
		}

		// cookiebar actie als er op OK geklikt wordt
		var $j = jQuery.noConflict();
		$j(function(){
			$j("#removecookie").click(function () {
				SetCookie('catapult-cookie','catapult-cookie',60);
                var cookieD2 = new Date();
                cookieD2.setTime(cookieD2.getTime() + (30*24*60*60*1000));
                var expires = "expires="+ cookieD2.toUTCString();
                document.cookie = 'CookieTagDerden' + "=" + 'True' + ";" + expires + ";path=/";
				$j("#catacookielaw").fadeOut("slow");
				location.reload();
			});
		});
		</script>

		<style type="text/css">
			<?php
			if (get_option ('cookiebar_kleur') != "") {
				echo "#catacookielaw p { color:".get_option ('cookiebar_button_tekst_kleur')."!IMPORTANT;}";
				echo "#catacookielaw { background-color:".get_option ('cookiebar_kleur')."!IMPORTANT;}";
				echo "#catacookielaw a#removecookie { background-color:".get_option ('cookiebar_button_kleur')."!IMPORTANT;}";
			}
			?>
		</style>

		<script>
			jQuery(document).ready(function(){
				jQuery("#catacookielaw-setting").click(function () {
					jQuery("#settingscookie").trigger("click");
				});
			});
		</script>
		<div id="catacookielaw-setting" style="display: none;">
			<a class="setting-cookies-linksonderin"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU0IDU0IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1NCA1NDsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiPgo8Zz4KCTxwYXRoIGQ9Ik01MS4yMiwyMWgtNS4wNTJjLTAuODEyLDAtMS40ODEtMC40NDctMS43OTItMS4xOTdzLTAuMTUzLTEuNTQsMC40Mi0yLjExNGwzLjU3Mi0zLjU3MSAgIGMwLjUyNS0wLjUyNSwwLjgxNC0xLjIyNCwwLjgxNC0xLjk2NmMwLTAuNzQzLTAuMjg5LTEuNDQxLTAuODE0LTEuOTY3bC00LjU1My00LjU1M2MtMS4wNS0xLjA1LTIuODgxLTEuMDUyLTMuOTMzLDBsLTMuNTcxLDMuNTcxICAgYy0wLjU3NCwwLjU3My0xLjM2NiwwLjczMy0yLjExNCwwLjQyMUMzMy40NDcsOS4zMTMsMzMsOC42NDQsMzMsNy44MzJWMi43OEMzMywxLjI0NywzMS43NTMsMCwzMC4yMiwwSDIzLjc4ICAgQzIyLjI0NywwLDIxLDEuMjQ3LDIxLDIuNzh2NS4wNTJjMCwwLjgxMi0wLjQ0NywxLjQ4MS0xLjE5NywxLjc5MmMtMC43NDgsMC4zMTMtMS41NCwwLjE1Mi0yLjExNC0wLjQyMWwtMy41NzEtMy41NzEgICBjLTEuMDUyLTEuMDUyLTIuODgzLTEuMDUtMy45MzMsMGwtNC41NTMsNC41NTNjLTAuNTI1LDAuNTI1LTAuODE0LDEuMjI0LTAuODE0LDEuOTY3YzAsMC43NDIsMC4yODksMS40NCwwLjgxNCwxLjk2NmwzLjU3MiwzLjU3MSAgIGMwLjU3MywwLjU3NCwwLjczLDEuMzY0LDAuNDIsMi4xMTRTOC42NDQsMjEsNy44MzIsMjFIMi43OEMxLjI0NywyMSwwLDIyLjI0NywwLDIzLjc4djYuNDM5QzAsMzEuNzUzLDEuMjQ3LDMzLDIuNzgsMzNoNS4wNTIgICBjMC44MTIsMCwxLjQ4MSwwLjQ0NywxLjc5MiwxLjE5N3MwLjE1MywxLjU0LTAuNDIsMi4xMTRsLTMuNTcyLDMuNTcxYy0wLjUyNSwwLjUyNS0wLjgxNCwxLjIyNC0wLjgxNCwxLjk2NiAgIGMwLDAuNzQzLDAuMjg5LDEuNDQxLDAuODE0LDEuOTY3bDQuNTUzLDQuNTUzYzEuMDUxLDEuMDUxLDIuODgxLDEuMDUzLDMuOTMzLDBsMy41NzEtMy41NzJjMC41NzQtMC41NzMsMS4zNjMtMC43MzEsMi4xMTQtMC40MiAgIGMwLjc1LDAuMzExLDEuMTk3LDAuOTgsMS4xOTcsMS43OTJ2NS4wNTJjMCwxLjUzMywxLjI0NywyLjc4LDIuNzgsMi43OGg2LjQzOWMxLjUzMywwLDIuNzgtMS4yNDcsMi43OC0yLjc4di01LjA1MiAgIGMwLTAuODEyLDAuNDQ3LTEuNDgxLDEuMTk3LTEuNzkyYzAuNzUxLTAuMzEyLDEuNTQtMC4xNTMsMi4xMTQsMC40MmwzLjU3MSwzLjU3MmMxLjA1MiwxLjA1MiwyLjg4MywxLjA1LDMuOTMzLDBsNC41NTMtNC41NTMgICBjMC41MjUtMC41MjUsMC44MTQtMS4yMjQsMC44MTQtMS45NjdjMC0wLjc0Mi0wLjI4OS0xLjQ0LTAuODE0LTEuOTY2bC0zLjU3Mi0zLjU3MWMtMC41NzMtMC41NzQtMC43My0xLjM2NC0wLjQyLTIuMTE0ICAgUzQ1LjM1NiwzMyw0Ni4xNjgsMzNoNS4wNTJjMS41MzMsMCwyLjc4LTEuMjQ3LDIuNzgtMi43OFYyMy43OEM1NCwyMi4yNDcsNTIuNzUzLDIxLDUxLjIyLDIxeiBNNTIsMzAuMjIgICBDNTIsMzAuNjUsNTEuNjUsMzEsNTEuMjIsMzFoLTUuMDUyYy0xLjYyNCwwLTMuMDE5LDAuOTMyLTMuNjQsMi40MzJjLTAuNjIyLDEuNS0wLjI5NSwzLjE0NiwwLjg1NCw0LjI5NGwzLjU3MiwzLjU3MSAgIGMwLjMwNSwwLjMwNSwwLjMwNSwwLjgsMCwxLjEwNGwtNC41NTMsNC41NTNjLTAuMzA0LDAuMzA0LTAuNzk5LDAuMzA2LTEuMTA0LDBsLTMuNTcxLTMuNTcyYy0xLjE0OS0xLjE0OS0yLjc5NC0xLjQ3NC00LjI5NC0wLjg1NCAgIGMtMS41LDAuNjIxLTIuNDMyLDIuMDE2LTIuNDMyLDMuNjR2NS4wNTJDMzEsNTEuNjUsMzAuNjUsNTIsMzAuMjIsNTJIMjMuNzhDMjMuMzUsNTIsMjMsNTEuNjUsMjMsNTEuMjJ2LTUuMDUyICAgYzAtMS42MjQtMC45MzItMy4wMTktMi40MzItMy42NGMtMC41MDMtMC4yMDktMS4wMjEtMC4zMTEtMS41MzMtMC4zMTFjLTEuMDE0LDAtMS45OTcsMC40LTIuNzYxLDEuMTY0bC0zLjU3MSwzLjU3MiAgIGMtMC4zMDYsMC4zMDYtMC44MDEsMC4zMDQtMS4xMDQsMGwtNC41NTMtNC41NTNjLTAuMzA1LTAuMzA1LTAuMzA1LTAuOCwwLTEuMTA0bDMuNTcyLTMuNTcxYzEuMTQ4LTEuMTQ4LDEuNDc2LTIuNzk0LDAuODU0LTQuMjk0ICAgQzEwLjg1MSwzMS45MzIsOS40NTYsMzEsNy44MzIsMzFIMi43OEMyLjM1LDMxLDIsMzAuNjUsMiwzMC4yMlYyMy43OEMyLDIzLjM1LDIuMzUsMjMsMi43OCwyM2g1LjA1MiAgIGMxLjYyNCwwLDMuMDE5LTAuOTMyLDMuNjQtMi40MzJjMC42MjItMS41LDAuMjk1LTMuMTQ2LTAuODU0LTQuMjk0bC0zLjU3Mi0zLjU3MWMtMC4zMDUtMC4zMDUtMC4zMDUtMC44LDAtMS4xMDRsNC41NTMtNC41NTMgICBjMC4zMDQtMC4zMDUsMC43OTktMC4zMDUsMS4xMDQsMGwzLjU3MSwzLjU3MWMxLjE0NywxLjE0NywyLjc5MiwxLjQ3Niw0LjI5NCwwLjg1NEMyMi4wNjgsMTAuODUxLDIzLDkuNDU2LDIzLDcuODMyVjIuNzggICBDMjMsMi4zNSwyMy4zNSwyLDIzLjc4LDJoNi40MzlDMzAuNjUsMiwzMSwyLjM1LDMxLDIuNzh2NS4wNTJjMCwxLjYyNCwwLjkzMiwzLjAxOSwyLjQzMiwzLjY0ICAgYzEuNTAyLDAuNjIyLDMuMTQ2LDAuMjk0LDQuMjk0LTAuODU0bDMuNTcxLTMuNTcxYzAuMzA2LTAuMzA1LDAuODAxLTAuMzA1LDEuMTA0LDBsNC41NTMsNC41NTNjMC4zMDUsMC4zMDUsMC4zMDUsMC44LDAsMS4xMDQgICBsLTMuNTcyLDMuNTcxYy0xLjE0OCwxLjE0OC0xLjQ3NiwyLjc5NC0wLjg1NCw0LjI5NGMwLjYyMSwxLjUsMi4wMTYsMi40MzIsMy42NCwyLjQzMmg1LjA1MkM1MS42NSwyMyw1MiwyMy4zNSw1MiwyMy43OFYzMC4yMnoiIGZpbGw9IiMzMzMzMzMiLz4KCTxwYXRoIGQ9Ik0yNywxOGMtNC45NjMsMC05LDQuMDM3LTksOXM0LjAzNyw5LDksOXM5LTQuMDM3LDktOVMzMS45NjMsMTgsMjcsMTh6IE0yNywzNGMtMy44NTksMC03LTMuMTQxLTctN3MzLjE0MS03LDctNyAgIHM3LDMuMTQxLDcsN1MzMC44NTksMzQsMjcsMzR6IiBmaWxsPSIjMzMzMzMzIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></a>
		</div>
		
	<?php
	}
}

// DASHBOARD CODE
function my_login_logo() {
	wp_register_style( 'eigen_admin_stylesheet', plugins_url( '/css/eigen.css?v=1.8.3', __FILE__ ) );
	wp_enqueue_style( 'eigen_admin_stylesheet' );
?>
   	<script src="https://use.fontawesome.com/6051b8d857.js"></script>
	<style type="text/css">
		body.login{
			background-color: #FFF;
			<?php echo 'background-image:url(' . plugins_url( '/images/achtergrondcc.jpg', __FILE__ ) . '); '; ?>
			background-size: cover;
		}

		body.login div#login h1 a {
			<?php echo 'background-image:url(' . plugins_url( '/images/Logo-animated.gif', __FILE__ ) . '); '; ?>
			padding-bottom: 30px;
			background-size: 60%;
			width: 260px;
			background-position: center;
		}
	</style>
   
	<div id="wp-login-menu-links">
		<ul class="wp-login-menu">
			<li>
				<a href="<?php echo site_url(); ?>"><i class="fa fa-home wp-login-home-button" aria-hidden="true"></i></a>
				<span class="tooltiptext">Homepage</span>
			</li>
			<li>
				<a href="<?php echo site_url(); ?>/wp-login.php?action=lostpassword"><i class="fa fa-key" aria-hidden="true"></i></a>
				<span class="tooltiptext">Wachtwoord vergeten</span>
			</li>
		</ul>
	</div>
<?php 
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );
?>