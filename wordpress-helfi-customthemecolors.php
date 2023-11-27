<?php
/**
 * Plugin Name:     HDS Customize Theme Colors
 * Description:     Select your own primary, secondary and accent color combinations for Helsinki Theme
 * Text Domain:     wordpress-helfi-customthemecolors
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package Wordpress_Helfi_Customthemecolors
 */

namespace CityOfHelsinki\WordPress\CustomThemeColors;

/**
 * Constants
*/
define( __NAMESPACE__ . '\\PLUGIN_VERSION', '1.0.1' );
define( __NAMESPACE__ . '\\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$path = PLUGIN_PATH;
require_once $path . 'classes/class-themecolors.php';

add_action( 'init', array( ThemeColors::get_instance(), 'register' ) );
