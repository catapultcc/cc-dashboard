<?php 
/*Plugin Name: CC Dashboard
Plugin URI: https://www.catapult.nl
Description: Custom dashboard voor Catapult
Version: 1.8.2
Author: Xuwei Hu
*/
require_once('updater.class.php');
if(is_admin()) {
	$updater = new GitHubPluginUpdater(__FILE__, 'catapultcc', "cc-dashboard");
}

function catapult_admin_styles(){
	wp_register_style( 'catapult_admin_stylesheet', plugins_url( '/css/style.css?v=1.8.0', __FILE__ ) );
	wp_enqueue_style( 'catapult_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'catapult_admin_styles' );

function catapult_admin_image () {
	echo '<img src="' . plugins_url( 'images/help.png' , __FILE__ ) . '"><p>Heeft u vragen? Stuur ons een mail op <a href="mailto:communicatie@catapult.nl">communicatie@catapult.nl</a> of bel met 0548-654954</p>';
}
add_filter( 'admin_footer_text', 'catapult_admin_image');


if (get_theme_mod("cookie_melding")) {
	// TOEVOEGEN COOKIEMELDING
	add_action( 'wp_footer', 'catapult_cookie_functie' );
	function catapult_cookie_functie() {

	if(!isset($_COOKIE['catacookie'])){ ?>
		<script type="text/javascript">
		function SetCookie(c_name,value,expiredays)
		{
			var exdate=new Date()
			exdate.setDate(exdate.getDate()+expiredays)
			document.cookie=c_name+ "=" +escape(value)+";path=/"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString()) 
		}
		</script>
		<div id="catacookielaw" style="display: none;">
			<div class="catacookielaw-left">
				<?php 
				if ( function_exists('icl_object_id') ) {
					if(ICL_LANGUAGE_CODE=='nl') :
						echo "<p>Deze website gebruikt cookies om gebruikersstatistieken te meten. Als je doorgaat gaan wij ervan uit dat je dit accepteert.</p>";
					else : 
						echo "<p>This website uses cookies to measure user statistics.</p>";
					endif;
				} 
				else { 
					echo "<p>Deze website gebruikt cookies om gebruikersstatistieken te meten. Als je doorgaat gaan wij ervan uit dat je dit accepteert.</p>";
				}
				?>
				
			</div>
			<div class="catacookielaw-right">
				<a id="removecookie">Ok</a>
			</div>
		</div>

		<script type="text/javascript">
		var $j = jQuery.noConflict();
		$j(function(){
			if( document.cookie.indexOf("catacookie") ===-1 ){
				$j("#catacookielaw").show();
			}
			$j("#removecookie").click(function () {
				SetCookie('catacookie','catacookie',365)
				$j("#catacookielaw").fadeOut("slow");
			});
		});
		</script>
<style type="text/css">#catacookielaw { display:none }#catacookielaw a,#catacookielaw p{display:block;font-family:Helvetica,Calibri,Arial,sans-serif;font-size:13px;letter-spacing:.6px}#catacookielaw{position:fixed;left:0;width:100%;display:inline-block;background-color:#efefef;animation:slide-up-fade-in ease 2s;bottom:0;z-index:99999}#catacookielaw p{margin:0 auto;padding:8px;color:#6b6b6b}#catacookielaw a{margin:0;color:#fff;padding:7px;cursor:pointer}#catacookielaw a:hover{background-color:#4B4B4B}#catacookielaw .catacookielaw-left{float:left}#catacookielaw .catacookielaw-right{width:75px;float:right;background-color:#333;text-align:center}@keyframes slide-up-fade-in{from{opacity:0;transform:translate(0,60px)}to{opacity:1;transform:translate(0,0)}} @media only screen and (max-width : 480px) {#catacookielaw .catacookielaw-left {width: 75%;}#catacookielaw .catacookielaw-right {width: 25%;}#catacookielaw p { margin: 0 auto; padding: 6px; color: #6b6b6b; line-height: 125%; font-size: 12px; }#catacookielaw a {padding: 9px;} }@media only screen and (max-width : 768px) {#catacookielaw .catacookielaw-left {width: 80%;}#catacookielaw .catacookielaw-right {width: 20%;}#catacookielaw p { margin: 0 auto; padding: 7px 0 7px 15px; color: #6b6b6b; line-height: 125%; font-size: 11.5px; }#catacookielaw a {padding: 9px;} }#adminmenu .update-plugins.wf-menu-badge {background-color: #ff5f00 !important;}</style>
<?php
		}
	}
}
function my_login_logo() {
	wp_register_style( 'eigen_admin_stylesheet', plugins_url( '/css/eigen.css?v=1.8.0', __FILE__ ) );
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
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
?>