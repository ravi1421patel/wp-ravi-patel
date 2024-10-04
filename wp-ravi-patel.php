<?php
/*
Plugin Name: WP Ravi Patel
Description: This plugin creates a custom product and coupon menu.
Version: 1.0
*/
if(!class_exists('wp_ravi_patel')){
    class wp_ravi_patel{
        public function __construct() {
            register_activation_hook( __FILE__, array($this,'custom_product_plugin_activate') );    
            add_action( 'plugins_loaded', array($this,'custom_product_plugin_load_textdomain') );
            add_action('admin_enqueue_scripts', array($this,'custom_product_enqueue_script'));
        }

        public function custom_product_plugin_activate() {
            global $wpdb;
        
            $table_name = $wpdb->prefix . 'coupons_ravi_patel';
        
            $charset_collate = $wpdb->get_charset_collate();
        
            $sql = "CREATE TABLE  `wp_coupon_ravi_patel` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) DEFAULT NULL,
                `description` text DEFAULT NULL,
                `coupon_amt` decimal(10,2) DEFAULT NULL,
                `image` text DEFAULT NULL,
                `category` enum('category1','category2','category3') DEFAULT NULL,
                `availability` text DEFAULT NULL,
                `featured` enum('1','0') DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
              ) $charset_collate;";
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        public function custom_product_plugin_load_textdomain(){
            load_plugin_textdomain( 'wp-ravi-patel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        public function custom_product_enqueue_script(){
            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            wp_enqueue_script('wp_ravi_patel', plugins_url('assets/js/wp_ravi_patel.js', __FILE__), array('jquery'), '1.0', true);
            wp_enqueue_style('wp_ravi_patel', plugins_url('assets/css/wp_ravi_patel.css', __FILE__), array(), 1, false);
        }

    }
    $pluginObj = new wp_ravi_patel();
}
require_once plugin_dir_path( __FILE__ ) . 'admin/product.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/coupon_list.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/coupon_add_edit.php';


?>
