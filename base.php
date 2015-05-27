<?php
/*
Plugin Name: Base
Plugin URI: #
Description:
Author: Sa Truong
Version: 1.6
Author URI: #
*/

define('BASE_URL', trailingslashit( plugins_url('', __FILE__) ) );
define('BASE_PATH', trailingslashit(plugin_dir_path( __FILE__)));


final class  Base_Plugin{
    /**
     * @var string
     */
    public $version = '1.0';

    public $url =  BASE_URL;
    public $path =  BASE_PATH;

    protected static $_instance = null;

    /**
     * WooCommerce Constructor.
     */
    public function __construct() {


        //do_action( 'woocommerce_loaded' );
    }

    public function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function load_add_ons(){
        //$add_ons = array('test.php');
        include_once $this->path.'add-ons/test.php';
    }

    public function includes(){
        include_once $this->path.'includes/class-helper.php';
    }


}


/**
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @since  2.1
 * @return WooCommerce
 */
function BASE() {
    return Base_Plugin::instance();
}

// Global for backwards compatibility.
$GLOBALS['BASE'] = BASE();