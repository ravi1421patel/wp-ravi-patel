<?php
if(!class_exists('product_class')){
    class product_class{
        public function __construct(){
            add_action( 'admin_menu', array($this,'product_menu_page') );
        }
        public function product_menu_page() {
            // Add top-level menu page
            add_menu_page(
                __('Products','wp-ravi-patel'),
                'Products',       
                'manage_options',
                'list-coupon',   
                '',
                'dashicons-admin-plugins',  
                30                   
            );
        }
    }
    $productObj = new product_class();
}

?>