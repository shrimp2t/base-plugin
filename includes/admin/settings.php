<h2 class="nav-tab-wrapper">
    <?php
    $tabs = array(
        'general' => 'General',
        'products' => 'products',
        'tax' => 'Tax',
        'checkout' => 'Checkout',
        'emails' => 'Emails',
        'webhooks' => 'Webhooks',
    );
    $tabs = apply_filters('sa_admin_settings_tabs', $tabs);
    $current_tab = sanitize_title(  $_REQUEST['tab'] );
    ?>
    <?php
    foreach( $tabs as $k => $tab ){ ?>
    <a class="nav-tab <?php echo ( $k == $current_tab ||  ($current_tab == '' && $k == 'general' ) ) ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array('tab'=> $k ), SA_Admin::get_current_url() ) ); ?>"><?php echo esc_html(  $tab ); ?></a>
    <?php } ?>
</h2>

<form action="<?php echo esc_url( add_query_arg( array('tab'=> $current_tab ), SA_Admin::get_current_url() ) ); ?>" method="post">
<?php
do_action('sa_admin_settings_tab_content', $current_tab );
?>
</form>
