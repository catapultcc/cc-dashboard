<?php
/*Auto update class from github repo*/
class GitHubPluginUpdater {
	private $slug; 
	private $pluginData; 
	private $username; 
	private $repo;
	private $pluginFile;
	private $gitHubAPIResult;
	private $accesToken;
	private $pluginActivated;
	
	function __construct($pluginFile, $gitHubUsername, $gitHubProjectName, $accesToken = '') {
		add_filter("pre_set_site_transient_update_plugins", array($this, "setTransitent"));
		add_filter( "plugins_api", array( $this, "setPluginInfo" ), 10, 3 );
		add_filter( "upgrader_pre_install", array( $this, "preInstall" ), 10, 3 );
        	add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );
		
		$this->pluginFile = $pluginFile;
		$this->username = $gitHubUsername;
		$this->repo = $gitHubProjectName;
		$this->accesToken = $accesToken;
	}
	
	// get information regarding our plugin from Wordpress
	private function initPluginData() {
		$this->slug = plugin_basename($this->pluginFile);
		$this->pluginData = get_plugin_data($this->pluginFile);
	}
	
	// get information regarding our plugin from Github
	public function getRepoReleaseInfo() {
		if(!empty($this->gitHubAPIResult)) {
			return;
		}
		
		// Github API query
		$url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";
		
		// Acces token for private repo
		if (!empty($this->accesToken)) {
			$url = add_query-arg(array("acces_token" => $this->accesToken), $url);
		}
		
		// Get API results
		$this->gitHubAPIResult = wp_remote_retrieve_body(wp_remote_get($url));
		if(!empty($this->gitHubAPIResult)) {
			$this->gitHubAPIResult = @json_decode($this->gitHubAPIResult);
		}
		
		// use latest release only
		if(is_array($this->gitHubAPIResult)) {
			$this->gitHubAPIResult = $this->gitHubAPIResult[0];
		}
	}
	
	// push in plugin version information to get the update notification
	public function setTransitent($transient) {
		// if plugin data is checked before don't recheck
		if(empty($transient->checked)) {
			return $transient;
		}
		
		// get plugin release info
		$this->initPluginData();
		$this->getRepoReleaseInfo();
		
		// Check versions
		$doUpdate = version_compare($this->gitHubAPIResult->tag_name, $transient->checked[$this->slug]);
		
		// Update the transient to include new plugin data
		if($doUpdate == 1) {
			$package = $this->gitHubAPIResult->zipball_url;
			
			// Acces token for private Github repo
			if(!empty($this->accesToken)) {
				$package = add_query_arg(array("acces_token" => $this->accesToken), $package);
			}
			
			// Include updated data
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $this->gitHubAPIResult->tag_name;
			$obj->url = $this->pluginData["PluginURI"];
			$obj->package = $package;
			$transient->response[$this->slug] = $obj;
		}
		
		return $transient;
	}
	
	// push in plugin version information to display in the details box
	public function setPluginInfo($false, $action, $response) {
		// Get plugin release info
		$this->initPluginData();
		$this->getRepoReleaseInfo();
		
		// If nothing is found, return nothing
		if (!isset($response->slug) || ($response->slug != $this->slug)) {
			return $false;
		}
		
		$pluginBanner = plugins_url( '/images/banner.png', __FILE__ );
		// Add our plugin information
		$response->last_update = $this->gitHubAPIResult->published_at;
		$response->slug = $this->slug;
		$response->name = $this->pluginData["Name"];
		$response->version = $this->gitHubAPIResult->tag_name;
		$response->author = $this->pluginData["AuthorName"];
		$response->homepage = $this->pluginData["PluginURI"];
		$response->banners["high"] = $pluginBanner;
		$response->banners["low"] = $pluginBanner;
		
		// Download link for zipfile
		$downloadLink = $this->gitHubAPIResult->zipball_url;
		
		// Acces token for private repo
		if(!empty($this->accesToken)) {
			$downloadLink = add_query_arg(array("acces_token" => $this->accesToken), $downloadLink);
		}
		$response->download_link = $downloadLink;
		
		// Parse Github release notes, using a Parsedown class
		require_once(plugin_dir_path(__FILE__) . "Parsedown.php");
		
		// Tabs for lightbox
		$response->sections = array(
			'description' => $this->pluginData["Description"],
			'changelog' => class_exists("Parsedown")
			? Parsedown::instance()->parse($this->gitHubAPIResult->body) 
			: $this->gitHubAPIResult->body
		);
		
		// Gets the required version of WP if available in changelog
		$matches = null;
		preg_match("/requires:\s([\d.]+)/i", $this->gitHubAPIResult->body, $matches);
		if(!empty($matches)) {
			if(is_array($matches)) {
				if(count($matches) > 1) {
					$response->requires = $matches[1];
				}
			}
		}
		
		// Gets tested version of WP if available in changelog
		$matches = null;
		preg_match("/tested:\s([\d\.]+)/i", $this->gitHubAPIResult->body, $matches);
		if(!empty($matches)) {
			if(is_array($matches)) {
				if(count($matches) > 1) {
					$response->tested = $matches[1];
				}
			}
		}
		
		return $response;
	}
	
	public function preInstall( $true, $args )
    {
        // Get plugin information
		$this->initPluginData();

		// Check if the plugin was installed before...
        $this->pluginActivated = is_plugin_active( $this->slug );
    }
	
	// perform additonal actions to succesfully install our plugin
	public function postInstall($true, $hook_extra, $result) {
		
		// Change plugin folder name back to original
		global $wp_filesystem;
		$pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
        $wp_filesystem->move( $result['destination'], $pluginFolder );
        $result['destination'] = $pluginFolder;
		
		// Re-activate plugin if needed
		if ( $this->pluginActivated )
		{
			$activate = activate_plugin( $this->slug );
		}
		return $result;
	}
}

?>
