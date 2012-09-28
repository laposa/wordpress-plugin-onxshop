<?php
/*
Plugin Name: Onxshop
Plugin URI: http://onxshop.com/
Description: Integrate Onxshop content into your WordPress website. Using shortcode.
Version: 0.1 
Requires at least: 3.0
Author: Laposa Ltd
Author URI: http://laposa.co.uk/
License: BSD
*/

define('ONXSHOP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

/**
 * Onxshop folder detect via symbolic link
 */
 
define('ONXSHOP_PROJECT_DIR_WP', realpath(dirname(__FILE__) . '/onxshop_project') . '/');

/**
 * OnxshopWordpress
 */
 
class OnxshopWordpress {

	/**
	 * initialize - hook into Wordpress
	 */
	 
	public static function initialize() {
		
		if (!defined('ONXSHOP_PROJECT_DIR_WPx')) {
			
			//self::installationHint();
			return false;
			
		}

		//turn on output buffering
		//add_action('init', 'ob_start');
		//add_action('wp_footer', 'ob_end_flush');

		add_shortcode( 'onxshop', array('OnxshopWordpress', 'processShortcode') );
		add_action('init', array('OnxshopWordpress', 'initOnxshop'));
		
	}
	
	/**
	 * Wordpress shortcode hook
	 *
	 * Examples
	 *
	 * [onxshop request='component/fortunes']
	 * [onxshop request='node~id=5~']
	 *
	 * Use shortcode in a PHP file (outside the post editor).
	 *
	 * echo do_shortcode("[onxshop request='component/fortunes']");
	 * echo do_shortcode("[onxshop request='node~id=5~']");
	 */
	 
	public function processShortcode($atts) {
		
		extract(shortcode_atts(array(
     		'request' => 'blank'
		), $atts));
		
		$content = self::processRequest($request);
		
		return "<!--BEGIN: onxshop_start --><div class='onxshop_wordpress'>{$content}</div><!-- END: onxshop_end -->";
     
	}
	
	/**
	 * init Onxshop
	 */
	
	public function initOnxshop() {
	
		global $OnxshopBootstrap;
		
		/**
		 * Include global configuration
		 */
		
		require_once(ONXSHOP_PROJECT_DIR_WP . 'conf/global.php');
		
		/**
		 * Set version
		 */
		
		define("ONXSHOP_VERSION", trim(file_get_contents(ONXSHOP_DIR . 'ONXSHOP_VERSION')));
		
		/**
		 * Set include paths
		 */
		
		set_include_path(ONXSHOP_PROJECT_DIR . PATH_SEPARATOR . ONXSHOP_DIR . PATH_SEPARATOR . ONXSHOP_DIR . 'lib/' . PATH_SEPARATOR . get_include_path());
		require_once('lib/onxshop.functions.php');
		
		/**
		 * Include Bootstrap
		 */
		
		require_once('lib/onxshop.bootstrap.php');
		
		
		/**
		 * Init Bootstrap
		 */
		
		$OnxshopBootstrap = new Onxshop_Bootstrap();
		
		/**
		 * Init pre-action
		 */
		
		$OnxshopBootstrap->initPreAction(array("autologin", "locales"));
		
		
	}
	
	/**
	 * process Onxshop request
	 */
	 
	public function processRequest($request) {
		
		global $OnxshopBootstrap;
		
		/**
		 * Init action
		 */
			
		$OnxshopBootstrap->initAction($request);
		
		/**
		 * Output content
		 */
		
		$content = $OnxshopBootstrap->getOutput();
	
		return $content;
		
	}
	
	/**
	 * installationHint
	 */
	 
	public function installationHint() {
		
		echo 'Please install Onxshop to ' . dirname(__FILE__) . '/onxshop_project/<br />';
		echo 'or create symbolic link to your current installation for example:<br />';
		echo '$ ln -s /srv/your_onxshop_project.com ' . dirname(__FILE__) . '/onxshop_project<br />';
		
	}
}

/**
 * initialize
 */

OnxshopWordpress::initialize();

