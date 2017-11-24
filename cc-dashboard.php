<?php 
/*Plugin Name: CC Dashboard
Plugin URI: https://www.catapult.nl
Description: Custom dashboard voor Catapult
Version: 1.7.6
Author: Xuwei Hu
*/

require_once('updater.class.php');
if(is_admin()) {
	$updater = new GitHubPluginUpdater(__FILE__, 'catapultcc', "cc-dashboard");
}

function catapult_admin_styles(){
	wp_register_style( 'catapult_admin_stylesheet', plugins_url( '/css/style.css', __FILE__ ) );
	wp_enqueue_style( 'catapult_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'catapult_admin_styles' );

function catapult_admin_image () {
	echo '<img src="' . plugins_url( 'images/help.png' , __FILE__ ) . '"><p>Heeft u vragen? Stuur ons een mail op <a href="mailto:communicatie@catapult.nl">communicatie@catapult.nl</a> of bel met 0548-654954</p>';
}
add_filter( 'admin_footer_text', 'catapult_admin_image');

function my_login_logo() {
	wp_register_style( 'eigen_admin_stylesheet', plugins_url( '/css/eigen.css', __FILE__ ) );
	wp_enqueue_style( 'eigen_admin_stylesheet' );
?>
   	<script src="https://use.fontawesome.com/6051b8d857.js"></script>
    
	<style type="text/css">
		body.login{
			background-color: #FFF;
			<?php echo 'background-image:url(' . plugins_url( '/images/pattern.svg', __FILE__ ) . '); '; ?>
			background-size: cover;
		}

		body.login div#login h1 a {
			<?php echo 'background-image:url(' . plugins_url( '/images/cc_icoon.svg', __FILE__ ) . '); '; ?>
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
