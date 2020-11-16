<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Settings_Page
 * @subpackage Settings_Page/includes
 * @author     Ben Shadle <benshadle@gmail.com>
 */

class Order_CSV_Stat_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->includeCSVGenerator();
	}

	public function includeCSVGenerator() {
		require_once 'partials/'.$this->plugin_name.'-generator.php';
  	}

	public static function activate() {
		
	}

}
