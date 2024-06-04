<?php get_header(); ?>
    <main class="d-flex flex-nowrap">
        
    <?php if ( is_user_logged_in() && current_user_can('editor')) {
        if( !session_id() )
        {
           session_start();
          }
        get_header(); 
        include (TEMPLATEPATH . '/inc/class.messages.php' ); 
        $msg = new Messages();
        $custom= get_user_meta(get_current_user_id(),'escola');
        if(isset($_POST['id_post'])){      
            extract($_POST);  
            $id_post = $custom[0];          
            $new_post = array(
                'ID' => $id_post,
                'post_type' => 'escola',
                'post_title' => $post_title
            );
            wp_update_post($new_post);
            update_post_meta($id_post, 'tipo_escola', $tipo_escola);      
            update_post_meta($id_post, 'cep', $cep);      
            update_post_meta($id_post, 'rua', $rua);  
            update_post_meta($id_post, 'numero', $numero);    
            update_post_meta($id_post, 'bairro', $bairro);   
            update_post_meta($id_post, 'turno', $turno,false);  
            update_post_meta($id_post, 'turmas_fundamental', $turmas_fundamental, false);
            update_post_meta($id_post, 'turmas_medio', $turmas_medio,false);
            $msg->add('s', 'Escola atualizada com sucesso!');	
        }
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
                                <h2 class="font-24 pb-2">Configurações</h2>
                            </div>
                        </div>
                    </div>

                    <?php $wp_query = new WP_Query(array('posts_per_page' => '1', 'post_type' => 'escola' ,'p'=>$custom[0]));?>
                    <?php if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); 
                    $tipo = get_field('tipo_escola');
                    ?>
                    
                    <h3 class="color-secondary fw-bold font-24">Informações</h3>
                    <form method="post" action="" class="validate">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="post_title">Escola</label>
                            <input type="text" class="form-control" name="post_title" id="post_title" value="<?php the_title();?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="tipo_escola">Tipo</label>
                            <select class="form-select" name="tipo_escola" id="tipo_escola">
                                <option value="estadual" <?php echo (get_field('tipo_escola')=='estadual')?'selected':'';?>>Estadual</option>
                                <option value="municipal" <?php echo (get_field('tipo_escola')=='municipal')?'selected':'';?>>Municipal</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="cep">CEP</label>
                            <input type="text" class="form-control" name="cep" id="cep" value="<?php the_field('cep');?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="rua">Rua</label>
                            <input type="text" class="form-control" name="rua" id="rua" value="<?php the_field('rua');?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="numero">Número</label>
                            <input type="text" class="form-control" name="numero" id="numero" value="<?php the_field('numero');?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold color-secondary mb-2" for="bairro">Bairro</label>
                            <input type="text" class="form-control" name="bairro" id="bairro" value="<?php the_field('bairro');?>">
                        </div>
                    </div>

                    <h3 class="color-secondary fw-bold font-24">Turnos</h3>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off" name="turno[]" value="matutino" <?php echo in_array("matutino", get_field('turno'))?'checked':'';?>>
                                <label class="btn btn-outline-primary" for="btncheck1">Matutino</label>

                                <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off" name="turno[]" value="vespertino" <?php echo in_array("vespertino", get_field('turno'))?'checked':'';?>>
                                <label class="btn btn-outline-primary" for="btncheck2">Vespertino</label>

                                <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off" name="turno[]" value="noturno" <?php echo in_array("noturno", get_field('turno'))?'checked':'';?>>
                                <label class="btn btn-outline-primary" for="btncheck3">Noturno</label>
                            </div>
                        </div>
                    </div>

                    <h3 class="color-secondary fw-bold font-24">Turmas</h3>


                    <div class="accordion mb-3" id="accordionTurmas">
                        <div class="accordion-item mb-1">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed color-primary fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFundamental" aria-controls="collapseFundamental">
                                    Fundamental
                                </button>
                            </h2>
                            <div id="collapseFundamental" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionTurmas">
                                <div class="accordion-body pt-0">
                                    <?php 
                                        for ($i = 1; $i <= 9; $i++) {
                                        echo '<div class="row border-bottom pb-3">';
                                        echo '<h2 class="font-16 mb-2 mt-3 fw-bold color-primary"> ' . $i . 'º ANO</h2>';
                                        for ($letra = 'A'; $letra <= 'F'; $letra++) {
                                    ?>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="<?php echo $i;?>_<?php echo strtolower($letra);?>" name="turmas_fundamental[]" id="fundamental_<?php echo $i;?><?php echo $letra;?>" <?php echo in_array($i.'_'.strtolower($letra), get_field('turmas_fundamental')) ? 'checked' : '';?>>
                                                <label class="form-check-label" for="fundamental_<?php echo $i;?><?php echo $letra;?>">
                                                    <?php echo $i;?>º <?php echo $letra;?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php } echo '</div>'; } ?>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-1">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed color-primary fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Médio
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionTurmas">
                                <div class="accordion-body">
                                    <?php 
                                        for ($i = 1; $i <= 3; $i++) {
                                            echo '<div class="row border-bottom pb-3">';
                                            echo '<h2 class="font-16 mb-2 mt-3 fw-bold color-primary"> ' . $i . 'ª ANO</h2>';
                                            for ($letra = 'A'; $letra <= 'F'; $letra++) {
                                    ?>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="<?php echo $i;?>_<?php echo strtolower($letra);?>" name="turmas_medio[]" id="medio_<?php echo $i;?><?php echo $letra;?>" <?php echo in_array($i.'_'.strtolower($letra), get_field('turmas_medio')) ? 'checked' : '';?>>
                                                        <label class="form-check-label" for="medio_<?php echo $i;?><?php echo $letra;?>">
                                                            <?php echo $i;?>ª <?php echo $letra;?>
                                                        </label>
                                                    </div>
                                                </div>
                                    <?php 
                                            }
                                            echo '</div>';
                                        } 
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-12 mb-3">
                        <input type="hidden" name="id_post" value="<?php echo $custom[0];?>"/>
                        <button class="btn btn-primary btn-sample" type="submit">Salvar</button>
                    </div>

                    </form>

                    <?php endwhile; ?>

                    <?php else : ?>

                        <div class="col-md-12 text-center mt-5" class="text-center">
                            <h3>Nenhuma cadastrada</h3>
                            <p>Clique aqui para cadastrar sua escola</p>
                            <a class="btn btn-primary btn-sample" href="#">Adicionar Escola</a>
                        </div>

                    <?php endif; ?>

                    <?php wp_reset_query(); ?>

                </div>
            </div>
        </div>

    <?php } else { 
        wp_redirect( bloginfo('url') . '/entrar' ); 
    } ?>

    </main>

<?php get_footer(); ?>