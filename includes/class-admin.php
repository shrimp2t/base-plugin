<?php

class SA_Admin{

    public static $path;
    public static $current_url;
    protected static $_instance = null;


    function __construct(){
        $this->instance();
        add_action('admin_menu', array( $this,'add_menus' ) );
        add_action('init', array( $this,'save_settings' ) );
        add_action('sa_admin_settings_tab_content', array( $this,'form_settings' ) );
    }

    public function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance =  true;
            self::$path = SA_Plugin::$path.'includes/admin/';
            self::$current_url = admin_url( '?'. remove_query_arg('tab', $_SERVER['QUERY_STRING'] ) );

        }
        return self::$_instance;
    }

    public static function get_current_url(){
        return self::$current_url;
    }

    public  function add_menus(){
        self::instance();
        $menus = array(
            array(
                'title' => __('SA Plugin','sa_plugin'),
                'slug' => 'sa-settings',
                'cap' => 'manage_options',
                //'func' => array(__CLASS__, 'load_settings')
            ),
            array(
                'title' => __('Settings','sa_plugin'),
                'slug' => 'sa-settings',
                'cap' => 'manage_options',
            ),
            array(
                'title' => __('Add-ons','sa_plugin'),
                'slug' => 'sa-add-ons',
                'cap' => 'manage_options',
            ),
            array(
                'title' => __('System status','sa_plugin'),
                'slug' => 'sa-status',
                'cap' => 'manage_options',
            )
        );
        $menus = apply_filters('SA_Plugin_Menus', $menus );
        $menu = current(  $menus );
        if( empty(  $menu['func'] ) ){
            $menu['func'] =  self::menu_callback_func();
        }
        add_menu_page( $menu['title'], $menu['title'], $menu['cap'], $menu['slug'], $menu['func'] );
        unset( $menus[0] );
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

    public function load_settings(){
        self::instance();

        $slug = sanitize_title($_REQUEST['page']);
        if( strpos($slug,'sa-')  !== false ){
            $template =  str_replace('sa-','', $slug);
            $template_file = apply_filters('sa_admin_settings_templates', self::$path.$template.'.php', $template );
            if( is_file( $template_file ) ){
                include $template_file;
            }
        }
        do_action('sa_admin_load_settings');
    }

    public  function save_settings(){

    }

    public function form_settings(  $tab = '' ){
        include_once SA_Plugin::$path.'includes/class-admin-form.php';

        $form_args = array(
            'general' => array(
                array(
                    'type'=>'text',
                    'name' =>'test',
                    'placeholder' =>'Placeholder',
                    'title'=>'Test text',
                    'default' => '',
                    'desc' => 'Desc_here'
                ),
                array(
                    'type'=>'textarea',
                    'name' =>'test',
                    'placeholder' =>'Placeholder',
                    'title'=>'Test text',
                    'default' => '',
                    'desc' => 'Desc_here'
                ),
                array(
                    'type'=>'radio',
                    'name' =>'Radio',
                    'title'=>'Radio',
                    'default' => '',
                    'list' => array(
                        'option_1' =>'option 1',
                        'option_2' =>'option 2',
                        'option_3' =>'option 3'
                    ),
                    'desc' =>'Desc here'
                ),
                array(
                    'type'=>'checkbox',
                    'name' =>'Checkbox',
                    'title'=>'Checkbox',
                    'multiple' => false,
                    'value' => 1,
                    'label' => 'Checkbox label',
                    'default' => '',
                    'desc' =>'Desc here'
                ),
                array(
                    'type'=>'checkbox',
                    'name' =>'Checkbox',
                    'title'=>'Checkbox',
                    'multiple' => true,
                    'list' => array(
                        'option_1' =>'option 1',
                        'option_2' =>'option 2',
                        'option_3' =>'option 3'
                    ),
                    'default' => '',
                    'desc' =>'Desc here'
                ),
                array(
                    'type'=>'select',
                    'name' =>'select',
                    'title'=>'select',
                    'multiple' => false,
                    'options' =>  array(
                        'option_1' =>'option 1',
                        'option_2' =>'option 2',
                        'option_3' =>'option 3'
                    ),
                    'default' => '',
                    'desc' =>'Desc here'
                ),

                array(
                    'type'=>'select',
                    'name' =>'select_multiple',
                    'title'=>'select multiple',
                    'multiple' => true,
                    'options' =>  array(
                        'option_1' =>'option 1',
                        'option_2' =>'option 2',
                        'option_3' =>'option 3'
                    ),
                    'default' => '',
                    'desc' =>'Desc here'
                ),
            )
        );

        $fields =  ( $tab != ''  && isset( $form_args[  $tab ] ) ) ?  $form_args[  $tab ]  :  false;

        if(  $tab == '' ){
            $fields = $form_args['general'];
        }
        if( $fields ){
            $form = new SA_Admin_Form( $fields );
            echo $form->render();
        }else{

        }


    }


}

new SA_Admin;

