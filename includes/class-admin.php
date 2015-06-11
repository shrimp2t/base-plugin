<?php

class SA_Admin{

    public static $path;
    public static $current_url;
    protected static $_instance = null;
    public $menus;


    function __construct(){
        $this->instance();
        add_action('admin_menu', array( $this,'add_menus' ) );
        add_action('sa_admin_settings_content', array( $this,'form_settings' ) );
    }

    public function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance =  true;
            self::$path = SA_Plugin::$path.'includes/admin/';
            self::$current_url = admin_url( 'admin.php?'. remove_query_arg('tab', $_SERVER['QUERY_STRING'] ) );

        }
        return self::$_instance;
    }


    public static function get_menus(){
        return apply_filters('SA_Plugin_Menus', array() );
    }

    public static function get_current_url(){
        return self::$current_url;
    }

    public  function add_menus(){
        self::instance();
        $menus =  self::get_menus();
        $menu = current(  $menus );
        if( empty(  $menu['func'] ) ){
            $menu['func'] =  self::menu_callback_func();
        }
        add_menu_page( $menu['title'], $menu['title'], $menu['cap'], $menu['slug'], $menu['func'] );
        // remove first item if menus
        array_shift($menus);
        if( !empty( $menus ) ) {
            foreach ($menus as $sub_menu) {
                if( empty(  $sub_menu['func'] ) ){
                    $sub_menu['func'] =  self::menu_callback_func();
                }
                add_submenu_page($menu['slug'], $sub_menu['title'], $sub_menu['title'], $sub_menu['cap'], $sub_menu['slug'], $sub_menu['func']);
            }
        }
    }

    private  static function menu_callback_func(){
        return array(  __CLASS__ , 'load_settings');
    }

    private  static function get_slug(){
        return sanitize_title($_REQUEST['page']);
    }

    public function load_settings(){
        self::instance();
        $slug = self::get_slug();
        $template =  'settings';
        $menus  = self::get_menus();
        $menu = $menus[ $slug ];
        $template_file = apply_filters('sa_admin_settings_templates', self::$path.$template.'.php', $template );
        if( is_file( $template_file ) ){
            include $template_file;
        }
        do_action('sa_admin_load_settings');
    }

    public function form_settings(  $tab = '' ){
        include_once SA_Plugin::$path.'includes/class-admin-form.php';

        $menus =  self::get_menus();
        $slug = self::get_slug();
        if( $menus[ $slug ] ['have_tabs'] ){
            if( $tab != '' && isset( $menus[  $slug ]['settings'][ $tab ] ) ){
                $fields = $menus[  $slug ]['settings'][ $tab ]['options'];
            }else{
                //$t = current( $menus[  $slug ]['settings'] );
                //$fields = $t['options'];
                $fields = array();
            }
        }else{
            $fields = $menus[ $slug ]['settings'];
        }

        $form = new SA_Admin_Form( $fields );
        echo $form->render();

    }

}

new SA_Admin;

