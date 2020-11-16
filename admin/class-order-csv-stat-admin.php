<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/admin
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Order_CSV_Stat_Admin {

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
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $data    The order data required for this plugin.
	 */
	private $data;
	
	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string   $customeremail    The order filter customer email data required for this plugin.
	 */
	private $customeremail;
	
	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string   $status    The order filter status data required for this plugin.
	 */
	private	$status;

	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool   $customer_empty    The order filter customer empty data required for this plugin.
	 */
	private $customer_empty;

	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool   $filter_date_empty    The order filter date emptyy data required for this plugin.
	 */
	private $filter_date_empty;

	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      date   $filterstartdate    The order filter start date data required for this plugin.
	 */
	private $filterstartdate;

	/**
	 * Filter data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      date   $filterstartdate    The order filter start date data required for this plugin.
	 */
	private $filterenddate;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);   
		add_action('admin_init', array( $this, 'registerAndBuildFields' ));

		add_action( 'manage_posts_extra_tablenav', array( $this, 'admin_order_list_export_csv_button'), 20, 1 );
		// hook into admin-ajax
		// the text after 'wp_ajax_' and 'wp_ajax_no_priv_' in the add_action() calls
		// that follow is what you will use as the value of data.action in the ajax
		// call in your JS
		$this->load_dependencies();
		
		// add_action('init', 'initWCCSVOrders');
		// add_action( 'woocommerce_after_register_post_type', array( $this, 'init' ) );
		
		// add_action( 'admin_post_print.csv', 'print_csv' );
		add_action( 'admin_enqueue_scripts', 'my_enqueue' );
		// if the ajax call will be made from JS executed when user is logged into WP,
		// then use this version
		add_action('wp_ajax_call_export_csv', array( $this, 'export_csv'));
		// if the ajax call will be made from JS executed when no user is logged into WP,
		// then use this version
		add_action('wp_ajax_nopriv_call_export_csv', array( $this, 'export_csv'));

		add_action( 'woocommerce_after_register_post_type', 'set_data' );

		add_action( 'wp_ajax_my_action', 'my_action' );

	}

	private function load_dependencies(){

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/order-csv-stat-generator.php'; 
	}

	function print_csv()
	{
    	if ( ! current_user_can( 'manage_options' ) )
        	return;

		$csv_output = '';

		$orders = filter_orders($this->get_data());

		// for ($i=0; $i < sizeof($orders) -1; $i++) {
		// 	$order = $order[$i];
		// 	for ($j=0; $j < sizeof($order) -1; $i++){
		// 		$csv_output .= $order[$j].",";
		// 	}
		// 	$csv_output .= "\n";
		// }

		header('Content-type: application/force-download;');
		header('Content-Disposition: attachment;filename="report.csv"');
		header('Cache-Control: max-age=0');
		// header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$outstream = fopen("php://output", "w");

		for ($i=0; $i < sizeof($orders) -1; $i++) {
			$order = $order[$i];
			for ($j=0; $j < sizeof($order) -1; $i++){
				$csv_output .= $order[$j].",";
			}
			$csv_output .= "\n";
			fputcsv($outstream, $csv_output);
		}
		fclose($outstream);
		exit();
		wp_die();

// foreach($result as $result)
// {
//     fputcsv($outstream, $result);
// }

// fclose($outstream);
// exit();
		


		// for ($j=0;$j<$i;$j++) {
        //     $csv_output .= $rowr[$j].",";
        // }
        // $csv_output .= "\n";
		
		// 	$csv_output .= $rowr[$j].",";
		// 	$csv_output .= "\n";

    	// output the CSV data
	}

	public function my_enqueue($hook) {
    	if( 'index.php' != $hook ) {
			// Only applies to dashboard panel
			return;
    	}
        
		wp_enqueue_script( 'ajax-script', plugins_url( '/admin/js/my_query.js', __FILE__ ), array('jquery') );

		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'ajax-script', 'ajax_object',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
			
	}

	public function my_action() {
		global $wpdb;
		$whatever = intval( $_POST['whatever'] );
		$whatever = 10;
        echo $whatever;
		wp_die();
	}

	function admin_order_list_export_csv_button( $which ) {
    	global $typenow;

    	if ( 'shop_order' === $typenow && 'top' === $which ) {
        ?>
        	<div class="alignright actions custom">
            	<a name="csvexp_ord" 
				onclick="getCSVExport()"
				style="height:32px;"
				class="button btn btn-success order-csv-gen"
				><?php
                	echo __( 'CSV Export', 'woocommerce' ); ?></a>
        		</div>
        	<?php
    	}
	}


function outputCsv() {
	// $csvf = "Order stat CSV as at ".time().".csv";
	
	//Download Link - it can be prettier
	$dlink = 'http://'.$_SERVER["SERVER_NAME"].'/request/download&file='.$csvf;
	//JSON response to be handled on the client side
	// $result = '{"success":1,"path":"'.$dlink.'","error":null}';
	// header('Content-type: application/force-download;');
	// header('Content-Disposition:attachment; filename='.$csvf);

	// if ( ! current_user_can( 'edit_others_shop_orders' ) )
    //     return;

    // header('Content-Type: application/csv');
    // header('Content-Disposition: attachment; filename='.$csvf.'');
    // header('Pragma: no-cache');

	// $isempty = empty( $assocDataArray );
	// if ( $isempty ){

	// 	echo 'Your orders are empty !';

	// }else{

	// 	$fp = fopen( $csvf, 'w+' );
	// 	fputcsv( $fp, array_keys( reset($assocDataArray) ) );

	// 	foreach ( $assocDataArray AS $values ):
	// 		fputcsv( $fp, $values );
	// 	endforeach;

	// 	rewind($fp);
	// 	// $csv_line = stream_get_contents($fp);
	// 	// return rtrim($csv_line);
	// }

	// fclose( $fp );

	// wp_die();

	// return $result;

	if ( ! current_user_can( 'manage_options' ) )
        	return;

		$csv_output = '';

		$orders = filter_orders($this->get_data());

		// for ($i=0; $i < sizeof($orders) -1; $i++) {
		// 	$order = $order[$i];
		// 	for ($j=0; $j < sizeof($order) -1; $i++){
		// 		$csv_output .= $order[$j].",";
		// 	}
		// 	$csv_output .= "\n";
		// }

		$csvf = "Order stat CSV as at ".time().".csv";
		//Download Link - it can be prettier
		$dlink = get_site_url();
		$dlink .= '/request/download&file='.$csvf;
		//JSON response to be handled on the client side
		$result = '{"success":1,"path":"'.$dlink.'","error":null}';

		header('Content-type: application/force-download;');
		header('Content-Disposition: attachment;filename="report.csv"');
		header('Cache-Control: max-age=0');
		// header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$outstream = fopen("php://outpfilter_date_emptyut", "w");

		for ($i=0; $i < sizeof($orders) -1; $i++) {
			$order = $order[$i];
			for ($j=0; $j < sizeof($order) -1; $i++){
				$csv_output .= $order[$j].",";
			}
			$csv_output .= "\n";
			fputcsv($outstream, $csv_output);
		}
		fclose($outstream);
		// echo wp_send_json($result);
		// // exit();
		// wp_die();

		return array( 'success' => true, 'data' => $response ); 
}


private function initWCCSVOrders(){
	$orders = wc_get_orders( array( 'numberposts' => -1 ) );
			var_dump( $orders );
}


private function get_data(){

	return $this->$data;

}



private function set_data(){

	$args = array(
		'numberposts' => -1,
	);

	if($this->$status !== "All"){
		array_push($args,array(
			'status' => $this->$status,
		));
	}
	if($this->$filter_date_empty !== "0"){

		array_push($args,array(
			'date_created'=> $this->$filterstartdate .'...'. $this->$filterenddate, 
		));
		
	}
	if(!$this->$customer_empty){

		array_push($args,array(
			'customer_id' => $this->$customeremail,
		));
	
	}

	$all_orders = wc_get_orders( $args );
	
	

	$csv_orders = array();

	array_push($csv_orders, array('Order number','Order placed date','Name of customer','Order status','Order total'));

	// if($this->$status === "All" and $this->$filterdate === "0" and $this->$customer_empty){
	foreach( $all_orders as $order ){
		
		$order_id = $order->get_order_key();
		$order_placed_date = is_null( $order->get_date_paid())? date_format($order->get_date_created(),"yy-m-d H:i:s") : date_format($order->get_date_paid(),"yy-m-d H:i:s");
		$ordered_customer_billing_fullname = $order->get_formatted_billing_full_name();
		$order_status = $order->get_status();
		$order_total = $order->get_total() . ' ' .$order->get_currency();
		array_push($csv_orders, array($order_id,$order_placed_date,$ordered_customer_billing_fullname,$order_status,$order_total));
	}
	$this->$data = $csv_orders;
}

function runOnInit() {
		
}

// function export_csv(){
// 	if (isset($_POST["order_csv_export"]))
// {

//        $filename = 'Student_Table_' . time() . '.csv';
//         $header_row = array(
//             'Order number',
// 			'Order placed date',
// 			'Name of customer',
// 			'Order',
// 			'Order total',
//         );
//        $data_rows = array();


//         $users = $wpdb->get_results("SELECT * FROM ".$table_prefix."student ","ARRAY_A");
//         foreach ( $users as $user ) 
//         {
//             $row = array(
//             $user['id'],
//             $user['f_name'],
//             $user['l_name'],
//             $user['email'],
//             $user['p_name'],
//             $user['address']
//             );
//             $data_rows[] = $row;
//         }
//         ob_end_clean ();
//         $fh = @fopen( 'php://output', 'w' );
//         header( "Content-Disposition: attachment; filename={$filename}" );
//         fputcsv( $fh, $header_row );
//         foreach ( $data_rows as $data_row ) 
//         {
//             fputcsv( $fh, $data_row );
//         }
    
        
//         exit();
//     }
// }

function export_csv() {
	$orders = array();
	
	$this->$customer_empty = ($_GET['customer_empty'] === 'true');

	$this->$filter_date_empty = ($_GET['filter_date_empty'] === 'true');

	$this->$filterdate = date_format($_GET['filterdate'],"yy-m-d H:i:s");

	$this->$status = $_GET['status'];
	
	if(!$this->$filter_date_empty){

		$this->$filterstartdate = $_GET['filterstartdate'];
		$this->$filterenddate = $_GET['filterenddate'];
	}

	if(!$this->$customer_empty){
		
		$this->$customeremail = $_GET['customeremail'];
	
	}
	// if(!current_user_can( 'edit_others_shop_orders' ))
	// {
	// 	echo "Sorry you don't have the permissions !";
			
	// }
	// else
	// {
	// 	if ( $_GET['action'] !== 'call_export_csv' and current_user_can( 'edit_others_shop_orders' )) {
		
	// 		// $generate = new Order_CSV_Stat_Generator();
	// 		// array_push($orders, $generate->get_data());
	// 		// echo(var_dump($orders));
	// 		$result = '{"success":-1,"error":"You do not have permissions fot this !"}';
	// 		return wp_send_json_error($result);
	
	// 	}
	$this->set_data();
	// $response = array( 'success' => true, 'data' => $this->outputCsv() );
		$response = array( 'success' => true, 'data' => $this->get_data() ); 
		return wp_send_json_success($response);
	// }
}



function filter_orders()
{
	$orders = $this->set_data();

	$filtered_orders = array();

	foreach ( $orders as $order ) {
		if($order instanceof WC_Order){
			$order_no = $order['ids'];
			$order_placed_date = $order['date_created'];
			$customer_name = $order['billing_first_name'] . ' ' . $order['billing_last_name'];
			$order_status = $order['status'];
			$order_total = $order['total'] + $order['currency'];
			array_push($filtered_orders, $order);
		}
	}

	return $filtered_orders;
}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/settings-page-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		 wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/settings-page-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function addPluginAdminMenu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  $this->plugin_name, 'Woorder CSV', 'administrator', $this->plugin_name, array( $this, 'displayPluginAdminDashboard' ), 'dashicons-cart', 26 );
		
		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page( $this->plugin_name, 'Settings Page Settings', 'Settings', 'administrator', $this->plugin_name.'-settings', array( $this, 'displayPluginAdminSettings' ));
	}
	
	public function displayPluginAdminDashboard() {
		require_once 'partials/'.$this->plugin_name.'-display.php';
  	}

	public function displayPluginAdminSettings() {
		// set this var to be used in the settings-display view
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
		if(isset($_GET['error_message'])){
				add_action('admin_notices', array($this,'settingsPageSettingsMessages'));
				do_action( 'admin_notices', $_GET['error_message'] );
		}
		require_once 'partials/'.$this->plugin_name.'-display.php';
	}
	public function settingsPageSettingsMessages($error_message){
		switch ($error_message) {
				case '1':
						$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain' );                 $err_code = esc_attr( 'settings_page_example_setting' );                 $setting_field = 'settings_page_example_setting';                 
						break;
		}
		$type = 'error';
		add_settings_error(
					$setting_field,
					$err_code,
					$message,
					$type
			);
	}
	public function registerAndBuildFields() {
			/**
		 * First, we add_settings_section. This is necessary since all future settings must belong to one.
		 * Second, add_settings_field
		 * Third, register_setting
		 */     
		add_settings_section(
			// ID used to identify this section and with which to register options
			'settings_page_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
				array( $this, 'settings_page_display_general_account' ),    
			// Page on which to add this section of options
			'settings_page_general_settings'                   
		);
		unset($args);
		$args = array (
							'type'      => 'input',
							'subtype'   => 'text',
							'id'    => 'settings_page_example_setting',
							'name'      => 'settings_page_example_setting',
							'required' => 'true',
							'get_options_list' => '',
							'value_type'=>'normal',
							'wp_data' => 'option'
					);
		add_settings_field(
			'settings_page_example_setting',
			'Example Setting',
			array( $this, 'settings_page_render_settings_field' ),
			'settings_page_general_settings',
			'settings_page_general_section',
			$args
		);


		register_setting(
						'settings_page_general_settings',
						'settings_page_example_setting'
						);

	}
	public function settings_page_display_general_account() {
		echo '<p>These settings apply to all Plugin Name functionality.</p>';
	} 
	public function settings_page_render_settings_field($args) {
			/* EXAMPLE INPUT
								'type'      => 'input',
								'subtype'   => '',
								'id'    => $this->plugin_name.'_example_setting',
								'name'      => $this->plugin_name.'_example_setting',
								'required' => 'required="required"',
								'get_option_list' => "",
									'value_type' = serialized OR normal,
			'wp_data'=>(option or post_meta),
			'post_id' =>
			*/     
		if($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		} elseif($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
		}

		switch ($args['type']) {

			case 'input':
					$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
					if($args['subtype'] != 'checkbox'){
							$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
							$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
							$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
							$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
							$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
							if(isset($args['disabled'])){
									// hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
									echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
							} else {
									echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
							}
							/*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/

					} else {
							$checked = ($value) ? 'checked' : '';
							echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" name="'.$args['name'].'" size="40" value="1" '.$checked.' />';
					}
					break;
			default:
					# code...
					break;
		}
	}
}
