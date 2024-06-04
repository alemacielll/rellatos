<?php 
    if ( is_user_logged_in() ) { 
        wp_redirect( bloginfo('url') . '/painel' );
    } else {
        wp_redirect( bloginfo('url') . '/entrar' );
        exit;
    } 
?>