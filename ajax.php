<?php
/*
Part of Onxshop Wordpress Plugin
Author: Laposa Ltd
Author URI: http://laposa.co.uk/
License: BSD
*/

/**
 * add this to your .htaccess file
 
# BEGIN onxshop
RewriteEngine On
RewriteRule ^request/(.*)$ /wp-content/plugins/onxshop/ajax.php?request=uri_mapping&controller_request=$1 [L,QSA]
# END

 */

/**
 * Onxshop folder detect via symbolic link
 */
 
define('ONXSHOP_PROJECT_DIR_WP', realpath(dirname(__FILE__) . '/onxshop_project') . '/');

if (!defined('ONXSHOP_PROJECT_DIR_WP')) {
	
	return false;

}

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


/**
 * Init action
 */
	
$OnxshopBootstrap->initAction(@$_GET['request']);

/**
 * Output content
 */

echo $Bootstrap->finalOutput();
