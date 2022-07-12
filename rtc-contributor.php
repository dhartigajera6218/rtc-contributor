<?php
/**
 * Plugin Name: RTC Contributor
 * Description: 
 * Author: Dharti Gajera
 * Version: 1.0.0
 * 
 */
defined('ABSPATH') or die('No script kiddies please!');


define('RTC_Contributor_PLUGIN_FILE', __FILE__);
define('RTC_Contributor_PLUGIN_DIR', __DIR__);


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/contributor-portal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
