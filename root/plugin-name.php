<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              {%= homepage %}
 * @since             {%= version %}
 * @package           {%= safe_name %}
 *
 * @wordpress-plugin
 * Plugin Name:       {%= title %}
 * Plugin URI:        {%= homepage %}
 * Description:       {%= description %}
 * Version:           {%= version %}
 * Author:            {%= author_name %} or Your Company
 * Author URI:        {%= homepage %}/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       {%= slug %}
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-{%= slug %}-activator.php
 */
function activate_{%= underscored_slug %}() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-{%= slug %}-activator.php';
	{%= safe_name %}_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-{%= slug %}-deactivator.php
 */
function deactivate_{%= underscored_slug %}() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-{%= slug %}-deactivator.php';
	{%= safe_name %}_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_{%= underscored_slug %}' );
register_deactivation_hook( __FILE__, 'deactivate_{%= underscored_slug %}' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-{%= slug %}.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    {%= version %}
 */
function run_{%= underscored_slug %}() {

	$plugin = new {%= safe_name %}();
	$plugin->run();

}
run_{%= underscored_slug %}();
