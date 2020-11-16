<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Settings_Page
 * @subpackage Settings_Page/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Settings_Page
 * @subpackage Settings_Page/includes
 * @author     Ben Shadle <benshadle@gmail.com>
 */


class Order_CSV_Stat_Generator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */


    /**
     * 
     * Access mode : private (data privacy policy)
     * set_data :: sets all order data from the woocommerce
     * @param  None no parameters.
     */
    private function set_data(){

        $query = new WC_Order_Query( array(
            'limit' => -1,
            'orderby' => 'date_created',
            'order' => 'DESC',
        ) );
        
        return $query->get_orders();
    
    }

    /**
     * 
     * Access mode : private (data privacy policy)
     * get_data :: gets all filtered order data from the woocommerce, 
     *             the data is being filtered as a privacy concern 
     * @param  None no parameters.
     */

    public function get_data(){
        return self::set_data();
    }

    //TBC : Other types to be implpemented in future with chartjs and more

}