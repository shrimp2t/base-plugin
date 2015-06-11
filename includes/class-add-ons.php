<?php
/**
 * Created by PhpStorm.
 * User: truongsa
 * Date: 5/29/15
 * Time: 11:51
 */

class SA_Add_Ons{

}


class SA_Add_Ons_Admin{

    function __construct(){
        add_action('sa_admin_settings_content', array( $this,'list_add_ons' ) );
    }

    function list_add_ons(){

    }

}

if( is_admin() ){
    new SA_Add_Ons_Admin;
}