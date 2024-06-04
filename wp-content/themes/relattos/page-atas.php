<?php get_header(); ?>
    <main class="d-flex flex-nowrap">
        
    <?php 
        if( !session_id() )
        {
        session_start();
        }
        include (TEMPLATEPATH . '/inc/class.messages.php' ); 
        $msg = new Messages();
        if ( is_user_logged_in() ) { ?>

        <?php include ('inc/menu.php'); ?>

        <?php
            $user_id = get_current_user_id();
            $args = array('posts_per_page' => 1,'post_type'=> 'escola','author'=> $user_id,);
            $wp_query = new WP_Query($args);
            if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();
                $escola = get_the_title();
            endwhile; else : endif; wp_reset_query();
        ?>
       
        <div class="flex-grow-1 scrollarea p-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-4 border-bottom">
                    <?php
                        echo $msg->display(); 
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="font-24 pb-2">Atas</h2>
                            </div>
                            <div class="col-md-6 text-end">
                                <?php if (!current_user_can('administrator')) : ?>
                                <a class="btn btn-primary btn-sample" href="<?php bloginfo('url');?>/nova-ata">Adicionar Ata</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="bg-secondary">Aluno</th>
                                    <th class="bg-secondary">Ação</th>
                                    <th class="bg-secondary">Autor</th>
                                    <th class="bg-secondary">Cargo</th>
                                    <th class="bg-secondary">Escola</th>
                                    <th class="bg-secondary">Data</th>
                                    <th class="bg-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $escola = get_user_meta(get_current_user_id(),'escola');
                                    $args = array(
                                        'posts_per_page' => '20',
                                        'post_type' => 'post',
                                    );       
                                    if (current_user_can('editor') || current_user_can('subscriber')) {                         
                                        $args['meta_key'] = "escola";
                                        $args['meta_value'] = $escola[0];
                                        $args['meta_compare'] = "=";   
                                    }     
                                    $wp_query = new WP_Query($args);

                                    if ($wp_query->have_posts()) : 
                                        while ($wp_query->have_posts()) : $wp_query->the_post(); 
                                            $cat = get_the_category();
                                            $author_id = get_post_field('post_author', get_the_ID);
                                            $escola = get_the_author_meta('escola', $author_id);
                                            $escola = get_the_title($escola);
                                            $author_id = get_post_field ('post_author', get_the_ID());
                                            $roles = get_userdata($author_id);
                                            $roles = $roles->roles[0];
                                            if ($escola) {
                                                $escola_title = $escola;
                                            } else {
                                                $escola_title = '';
                                            }
                                    ?>
                                            <tr>                                    
                                                <th><?php the_title(); ?></th>
                                                <td><?php echo $cat[0]->cat_name; ?></td>
                                                <td><?php the_author(); ?></td>
                                                <td><?php 
                                                if($roles == 'editor'){
                                                    echo 'Diretor';
                                                }else{
                                                    'Coordenador';
                                                }
                                                ?></td>
                                                <td><?php echo $escola_title; ?></td>
                                                <td><?php the_time('d/m/Y'); ?></td>
                                                <td>
                                                    <a target="_blank" class="font-20" href="<?php bloginfo('url'); ?>/gerar-pdf?id=<?php the_ID(); ?>"><i class="bi bi-filetype-pdf"></i></a>
                                                </td>
                                            </tr>
                                    <?php 
                                        endwhile; 
                                    else : 
                                    endif; 
                                    wp_reset_query(); 
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { 
        wp_redirect( bloginfo('url') . '/entrar' ); 
    } ?>

    </main>

<?php get_footer(); ?>