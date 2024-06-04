<?php 
        
    function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

    show_admin_bar( false );


    add_filter('admin_footer_text', 'bl_admin_footer');
    function bl_admin_footer() {
        echo 'Desenvolvido por <a target="_blank" href="https://argosolucoes.com.br">Argo Soluções</a>';
    }
    function my_login_url() { return get_option('home'); }
    function my_login_title() { return get_option('blogname'); }
    function custom_admin_title( $admin_title ) {
        return str_replace( ' &#8212; WordPress', '', $admin_title );
    }
    add_filter( 'admin_title', 'custom_admin_title' );



    function wp_responsivo_scripts() {
        wp_enqueue_style( 'bootstrap_css', get_template_directory_uri() . '/css/bootstrap.min.css' );
        wp_enqueue_style( 'icons_css', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css' );
        wp_enqueue_style( 'style', get_stylesheet_uri() );

        wp_enqueue_script('jquery_js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', array('jquery'), '', true );
        wp_enqueue_script('validate', get_template_directory_uri().'/js/jquery.validate.min.js',array('jquery'),'',true);
        wp_enqueue_script('maskedinput', get_template_directory_uri().'/js/jquery.maskedinput.min.js',array('jquery'),'',true);
        wp_enqueue_script('bootstrap_js', get_template_directory_uri().'/js/bootstrap.bundle.min.js', array('jquery'), '', true );
        wp_enqueue_script('custom_js', get_template_directory_uri().'/js/custom.js', array('jquery'), '', true );
    }
    add_action( 'wp_enqueue_scripts', 'wp_responsivo_scripts' );


    function move_yoast_below_acf() {
        return 'low';
    }
    add_filter( 'wpseo_metabox_prio', 'move_yoast_below_acf');
    function random_username($string) {
        $name = split("[ .-]", $string);
        $firstname = $name[0];
        $lastname = $name[1];
    
        $firstname = strtolower($firstname);
        $lastname = strtolower(substr($lastname, 0, 2));
        $nrRand = rand(0, 100);
        return $firstname . $lastname . $nrRand;
    }


    // changing name posts menu
    function revcon_change_post_label() {
    global $menu;
    global $submenu;
        $menu[5][0] = 'Atas';
        $submenu['edit.php'][5][0] = 'Atas';
        $submenu['edit.php'][10][0] = 'Nova Ata';
        $submenu['edit.php'][16][0] = 'Tags';
    }
    function revcon_change_post_object() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Atas';
        $labels->singular_name = 'Atas';
        $labels->add_new = 'Nova Ata';
        $labels->add_new_item = 'Nova Ata';
        $labels->edit_item = 'Editar Ata';
        $labels->new_item = 'Atas';
        $labels->view_item = 'Ver Atas';
        $labels->search_items = 'Procurar Atas';
        $labels->not_found = 'Nenhuma Ata Encontrada';
        $labels->not_found_in_trash = 'Nenhuma ata encontrada na lixeira';
        $labels->all_items = 'Todas as Atas';
        $labels->menu_name = 'Atas';
        $labels->name_admin_bar = 'Atas';
    }


    add_action( 'admin_menu', 'revcon_change_post_label' );
    add_action( 'init', 'revcon_change_post_object' );


    // changing dashicon menu posts
    function change_post_menu_label_icon() {
       global $menu;
       $menu[5][6] = 'dashicons-megaphone';
    }
    add_action( 'admin_menu', 'change_post_menu_label_icon' );

    
?>