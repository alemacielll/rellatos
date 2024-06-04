<?php get_header(); 
     if( !session_id() )
     {
        session_start();
       }
     include (TEMPLATEPATH . '/inc/class.messages.php' ); 
     $msg = new Messages();
?>

<main class="d-flex flex-nowrap">

    <?php if (is_user_logged_in()) {
        if(current_user_can('administrator')) {
            $args = array('role__in' => array('editor', 'subscriber'));                
            $usuarios = get_users($args);

           
        }else{
            $custom= get_user_meta(get_current_user_id(),'escola');
            $args = array(
                'role__in' => array('editor', 'subscriber'), 
                'meta_key'     => 'escola',
                'meta_value'   => $custom[0],
                'meta_compare' => '=', 
            );
            $user_query = new WP_User_Query($args);  
            $usuarios = $user_query->get_results();
        }
        
        ?>

        <?php include('inc/menu.php'); ?>

        <div class="flex-grow-1 scrollarea p-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-4 border-bottom">
                    <?php echo $msg->display(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="font-24 pb-2"><?php echo (current_user_can('administrator')) ? 'Usuários' : 'Equipe'; ?></h2>
                            </div>
                            <div class="col-md-6 text-end">
                                <a class="btn btn-primary btn-sample" href="<?php bloginfo('url'); ?>/novo-usuario/">Adicionar <?php echo (current_user_can('administrator')) ? 'Usuário' : 'Membro'; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="bg-secondary">ID</th>
                                    <th class="bg-secondary">Nome</th>
                                    <th class="bg-secondary">Função</th>
                                    <th class="bg-secondary">E-mail</th>
                                    <th class="bg-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($usuarios as $usuario) {
                                    $roles = $usuario->roles;
                                    $funcao = '';

                                    foreach ($roles as $role) {
                                        switch ($role) {
                                            case 'editor':
                                                $funcao = 'Diretor';
                                                break;
                                            case 'subscriber':
                                                $funcao = 'Coordenador';
                                                break;
                                        }
                                    }
                                ?>
                                    <tr>
                                    <th><?php echo $usuario->data->ID; ?></th>
                                        <th><?php echo $usuario->data->display_name; ?></th>
                                        <td><?php echo $funcao; ?></td>
                                        <th><?php echo $usuario->data->user_email; ?></th>
                                        <td>
                                            <a class="font-20" href="<?php bloginfo('url'); ?>/editar-usuario/?id=<?php echo $usuario->data->ID; ?>"><i class="bi bi-pencil"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php } else {
        wp_redirect(bloginfo('url') . '/entrar');
    } ?>

</main>

<?php get_footer(); ?>
