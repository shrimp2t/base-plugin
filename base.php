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


final class  SA_Plugin{
    /**
     * @var string
     */
    public $version = '1.0';

    public static $url =  BASE_URL;
    public static  $path =  BASE_PATH;
    protected static $_instance = null;

    /**
     *  Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->load_add_ons();
        $this->load_text_domain();

       // do_action( 'Base_Plugin' );
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

        // load admin settings
        if( is_admin() ){
            include_once $this->path.'includes/class-admin.php';
        }
    }

    public function load_text_domain(){

    }


}


/**
 * Returns the main instance of BASE to prevent the need to use globals.
 *
 * @since  1.0
 */
function BASE() {
    return SA_Plugin::instance();
}

// Global for backwards compatibility.
$GLOBALS['BASE'] = BASE();

//wp_get_theme();