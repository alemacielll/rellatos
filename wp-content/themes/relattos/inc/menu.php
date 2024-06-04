<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
    <a href="<?php bloginfo('url'); ?>" class="d-flex align-items-center justify-content-center align-items-center mb-3 me-ms-auto text-white text-decoration-none">
        <img src="<?php bloginfo('template_url'); ?>/img/logo.png" alt="" class="img-fluid">
    </a>
    <hr />

    <?php 
        global $post;
        $post_slug=$post->post_name;
        $type = get_post_type();
    ?>

    <ul class="nav nav-pills flex-column mb-auto">

    <li class="nav-item">
        <a href="<?php bloginfo('url'); ?>/painel" class="nav-link text-white <?php echo ($post_slug == 'painel') ? 'active' : ''; ?>" aria-current="page">
            <i class="bi bi-grid-1x2-fill"></i> Painel
        </a>
    </li>

    <li>
        <a href="<?php bloginfo('url'); ?>/atas" class="nav-link <?php echo ($post_slug == 'atas') ? 'active' : ''; ?> text-white">
            <i class="bi bi-list-check"></i> Atas
        </a>
    </li>
    <?php if (current_user_can('administrator')) : ?>
    <li>
        <a href="<?php bloginfo('url'); ?>/escolas" class="nav-link <?php echo ($post_slug == 'escolas') ? 'active' : ''; ?> text-white">
            <i class="bi bi-house-fill"></i> Escolas
        </a>
    </li>
    <?php endif; ?>
    <li>
        <a href="<?php bloginfo('url'); ?>/usuarios" class="nav-link <?php echo ($post_slug == 'usuarios') ? 'active' : ''; ?> text-white">
            <i class="bi bi-people-fill"></i> <?php echo (current_user_can('administrator')) ? 'Usuários' : 'Equipe'; ?>
        </a>
    </li>

    <?php if (current_user_can('editor')) : ?>
        <li>
            <a href="<?php bloginfo('url'); ?>/configuracoes" class="nav-link <?php echo ($post_slug == 'configuracoes') ? 'active' : ''; ?> text-white">
                <i class="bi bi-gear-fill"></i> Configurações
            </a>
        </li>
    <?php endif; ?>

</ul>


    <hr />

    <div class="dropdown">
        <?php
            $current_user = wp_get_current_user();
            if ($current_user->exists()) {
                $display_name = $current_user->display_name;
                if (empty($display_name)) {
                    $display_name = $current_user->user_email;
                }
            } else {
                $display_name = 'Visitante';
            }
        ?>

        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <strong><?php echo esc_html($display_name); ?></strong>
        </a>


        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="">
            <li><a class="dropdown-item" href="<?php bloginfo('url'); ?>/perfil">Perfil</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="<?php echo wp_logout_url(); ?>">Sair</a></li>
        </ul>
    </div>

</div>