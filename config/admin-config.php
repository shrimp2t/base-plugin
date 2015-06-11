<?php
/**
 * Created by PhpStorm.
 * User: truongsa
 * Date: 5/29/15
 * Time: 08:45
 */

class SA_Admin_config{

    var $menus = array();
    var $options = array();

    function __construct(){
        $this->init();
        add_filter('SA_Plugin_Menus',  array( $this, 'hooks' ) );
    }

    function init(){
        $this->add_menus();
    }
    function hooks(){
        return $this->menus;
    }
    public function add_menu( $menu_title, $slug, $cap = 'manage_options', $callback_func = false, $index_slug = true  ){
        if(  $slug == '' ){
            $slug =  sanitize_title( $menu_title );
        }
        $options = array(
            'title' => $menu_title,
            'slug' => $slug,
            'cap' => $cap,
            'func' => $callback_func,
            'have_tabs' => false
        );
        if( $index_slug ){
            $this->menus[ $slug ] = $options;
        }else{
            $this->menus[] = $options;
        }

    }

    /**
     * Add setting tab
     * @param string $menu_slug
     * @param  array $tab
     */
    function add_setting_tab( $menu_slug , $tab  ){
        if( ! is_array( $tab ) ){
            return;
        }

        if(  !isset( $this->menus [$menu_slug ] ) ){

            return;
        }
        $tab = wp_parse_args( $tab, array(
            'id' => 'general',
            'title' => __('General', 'sa_plugin')
        ) );
        if( $tab['id'] != '' ){
            $this->menus[ $menu_slug ]['have_tabs'] =  true;
        }else{

        }

        $this->menus[ $menu_slug ]['settings'][ $tab['id'] ] = array('title' => $tab['title'], 'options' => array() );

    }

    function add_setting_options( $menu_slug,  $options = array() ,  $tab_id = '' ){
        if( $tab_id != '' ){
            $this->menus[ $menu_slug ]['settings'][ $tab_id ]['options'] = $options ;
        }else{
            $this->menus[ $menu_slug ]['settings'] = $options;
        }

    }

    public function add_menus(){
        /**
         * All menu slug must start with "sa-" example "sa-settings"
         */
        $this->add_menu(  __('SA Plugin','sa-plugin'), 'sa-settings', 'manage_options',  false, false );
        $this->add_menu(  __('Settings','sa-plugin'), 'sa-settings' );
        $this->add_menu(  __('Add-ons','sa-plugin'), 'sa-add-ons' );
        $this->add_menu(  __('System status','sa-plugin'), 'sa-status' );

        //
        $this->add_setting_tab('sa-settings',array(
            'id' =>'general',
            'title' => 'General'
        ) );
        $this->add_setting_options( 'sa-settings',
            array(
                array(
                'type'=>'text',
                'name' =>'test',
                'placeholder' =>'Placeholder',
                'title'=>'Test text by add menu',
                'default' => '',
                'desc' => 'Desc_here'
                ),

                array(
                    'type'=>'editor',
                    'name' =>'editor',
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
                    )
                ), 'general');

        $this->add_setting_tab('sa-settings',array(
            'id' =>'product',
            'title' => 'Product'
        ) );
        $this->add_setting_options( 'sa-settings',
            array(
                array(
                    'type'=>'text',
                    'name' =>'product_test',
                    'placeholder' =>'Placeholder',
                    'title'=>'Product',
                    'default' => '',
                    'desc' => 'Desc_here'
                ),
            ),'product'
        );

    }

}

new SA_Admin_config();