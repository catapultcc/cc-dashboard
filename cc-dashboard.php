<?php 
/*Plugin Name: CC Dashboard
Plugin URI: https://www.catapult.nl
Description: Custom dashboard voor Catapult
Version: 1.3
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

function my_login_logo() { ?>
    
    <style type="text/css">
    	body.login{
    		background-color: #FFF;
			<?php echo 'background-image:url(' . plugins_url( '/images/background.jpg', __FILE__ ) . '); '; ?>
    		background-size: cover;
    	}

        body.login div#login h1 a {
			<?php echo 'background-image:url(' . plugins_url( '/images/logo-groot.png', __FILE__ ) . '); '; ?>
            padding-bottom: 30px;
            background-size: 100%;
			width: 260px;
        }

        body.login .button-primary{
        	background-color:#f08303;
        	border: 0;
        }

        body.login .button-primary:hover{
        	background-color:#e35500;
        }

        .login form{
        	box-shadow: 0 1px 20px rgba(0, 0, 0, 0.1);
        }
        a{
        	color:#FFF!important;
        }
        a:hover{
        	color:#FFF!important;
        }
		body.login #login {
			width: 320px;
			padding: 8% 0 0;
			margin: auto;
			background-color: #fff;
		}
		p#nav a{
			color: #333333 !important;
			}
		p#backtoblog a{
			color: #333333 !important;
			}
		p#backtoblog {
			padding-bottom: 20px !important;
			}
    </style>
    
    
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
?>
