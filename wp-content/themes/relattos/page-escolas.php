<?php get_header(); 
    if( !session_id() )
	{
	   session_start();
  	}
    include (TEMPLATEPATH . '/inc/class.messages.php' ); 
    $msg = new Messages();
    ?>
    <main class="d-flex flex-nowrap">
        
    <?php if ( is_user_logged_in()  && current_user_can('administrator')) { 
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
            'posts_per_page' => '30',
            'paged'          => $paged,
            'post_type' => 'escola'
        );
        $wp_query = new WP_Query($args);
        ?>

        <?php include ('inc/menu.php'); ?>

        <div class="flex-grow-1 scrollarea p-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-4 border-bottom">
                        <?php
                        echo $msg->display(); 
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="font-24 pb-2">Escolas</h2>
                            </div>
                            <div class="col-md-6 text-end">
                                <a class="btn btn-primary btn-sample" href="<?php bloginfo('url');?>/nova-escola/">Adicionar Escola</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="bg-secondary">ID</th>
                                    <th class="bg-secondary">Escola</th>
                                    <th class="bg-secondary">Diretor</th>
                                    <th class="bg-secondary">Tipo</th>
                                    <th class="bg-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=0; if($wp_query->have_posts()) :  ?>
                                <?php while($wp_query->have_posts()) : $wp_query->the_post(); $i++; ?>
                                <?php $theid = get_the_ID(); 
                                $diretor = get_field('diretor');
                                $tipo = get_field('tipo_escola');
                                $diretor_query = get_userdata( $diretor );
                                ?>
                                <tr>
                                    <th><?php echo the_ID(); ?></th>
                                    <th><?php the_title(); ?></th>
                                    <td><?php echo (!empty($diretor_query->data->display_name))?$diretor_query->data->display_name:'';?>
                                    </td>
                                    <td class="text-capitalize"><?php echo $tipo; ?></td>          
                                    <td>
                                        <a class="font-20" href="<?php the_permalink();?>"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php 
                                if($wp_query->max_num_pages>0){
                                    echo paginate_links( array( 	
                                        'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                                        'format' => '?paged=%#%',
                                        'current' => get_query_var('paged', 1),
                                        'total' => $wp_query->max_num_pages
                                    ) );
                                }
                                ?>
                                <?php else : ?>
                                    <!--Sem Escola-->
                            <?php endif; wp_reset_query(); ?>
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