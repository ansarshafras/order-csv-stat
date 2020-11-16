<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/public
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Order_CSV_Stat_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// add_action( 'admin_enqueue_scripts', array( 'Order_CSV_Stat_Internal_Pointers', 'enqueue_scripts' ) );
		// add_action( 'admin_enqueue_styles', array( 'Order_CSV_Stat_Internal_Pointers', 'enqueue_styles' ) );
		// add_action( 'admin_enqueue_scripts', 'my_enqueue' );
		// add_action( 'wp_ajax_my_action', 'my_action' );
	}
	// public function my_enqueue($hook) {
    // 	if( 'index.php' != $hook ) {
	// 		// Only applies to dashboard panel
	// 		return;
    // 	}
        
	// 	wp_enqueue_script( 'ajax-script', plugins_url( '/js/my_query.js', __FILE__ ), array('jquery') );

	// 	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	// 	wp_localize_script( 'ajax-script', 'ajax_object',
    //         array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
	// }

	// public function my_action() {
	// 	global $wpdb;
	// 	$whatever = intval( $_POST['whatever'] );
	// 	$whatever += 10;
    //     echo $whatever;
	// 	wp_die();
	// }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Settings_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Settings_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/settings-page-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Settings_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Settings_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/settings-page-public.js', array( 'jquery' ), $this->version, false );

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/settings-page-public.js', array( 'jquery' ), $this->version, false );



		wp_enqueue_script( 'ajaxHandle' );
  		wp_localize_script('ajaxHandle','ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));
	}

}
