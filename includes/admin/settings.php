<?php
if( $menu['have_tabs'] ){
?>
<h2 class="nav-tab-wrapper">
    <?php
        $current_tab = sanitize_title(  $_REQUEST['tab'] );
        foreach( $menu['settings'] as $k => $tab ){
            ?>
            <a class="nav-tab <?php echo ( $k == $current_tab ||  ($current_tab == '' && $k == 'general' ) ) ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array('tab'=> $k ), SA_Admin::get_current_url() ) ); ?>"><?php echo esc_html(  $tab['title'] ); ?></a>
            <?php
        }

    ?>
</h2>
<?php }else{
    ?>
    <h2><?php echo $menu['title']; ?></h2>
    <?php
}
?>
<form action="<?php echo esc_url( add_query_arg( array('tab'=> $current_tab ), SA_Admin::get_current_url() ) ); ?>" method="post">
<?php
do_action('sa_admin_settings_content', $current_tab, $slug );
?>
</form>
