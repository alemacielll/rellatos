<?php 
    if( !session_id() )
	{
	   session_start();
  	}
    include (TEMPLATEPATH . '/inc/class.messages.php' ); 
    $msg = new Messages();
    get_header(); ?>
    <main class="d-flex flex-nowrap">

    <?php if ( is_user_logged_in()  && current_user_can('administrator')) { ?>
    <?php include ('inc/menu.php'); 
        if(isset($_POST['new_user'])){
            
            extract($_POST);
            $name = explode(" ", $nome);
            $firstname = $name[0];
            $lastname = $name[1];        
            $verifica = get_user_by( 'email', $email );
            if(!empty($verifica)){
                $msg->add('e', 'Já existe esse e-mail!');	
            }else{
                $id_user = wp_insert_user( array(
                    'user_login' => $email,
                    'user_pass' => $senha,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstname),
                    'last_name' => ucfirst($lastname),
                    'display_name' => ucfirst($nome),
                    'role' => 'editor'
                ));
                $new_post = array(
                    'ID' => '',
                    'post_type' => 'escola',
                    'post_title' => $escola_nome,
                    'post_status' => 'publish'
                );
                $post_id = wp_insert_post($new_post);
                add_post_meta($post_id, 'diretor', $id_user);
                add_post_meta($post_id, 'tipo_escola', $tipo_escola);
                update_user_meta( $id_user, 'escola',$post_id );
                $msg->add('s', 'Escola cadastrada com sucesso!',home_url() . '/?post_type=escola&p=' . $post_id);
            }

        }
        
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
                            <h2 class="font-24 pb-2">Adicionar Escola</h2>
                        </div>
                        <div class="col-md-6 text-end">
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <h3 class="color-secondary fw-bold font-24">Informações</h3>
                </div>

                <div class="col-md-6">
                    <form method="post" action="" class="validate">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="escola_nome">Nome Escola</label>
                                <input type="text" placeholder="Informe o nome" class="form-control" id="escola_nome" name="escola_nome" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold color-secondary mb-2" for="escola_nome">Tipo Escola</label>
                                </div>
                                <div class="form-check ps-0 form-check-inline">
                                    <input type="radio" id="estadual" name="tipo_escola" value="estadual">
                                    <label for="estadual">Estadual</label><br>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="municipal" name="tipo_escola" value="municipal">
                                    <label for="municipal">Municipal</label><br>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="nome">Nome Diretor</label>
                                <input type="text" placeholder="Informe o nome" class="form-control" id="nome" name="nome" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="email">E-mail</label>
                                <input type="email" placeholder="Informe o E-mail" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="senha">Senha</label>
                                <input type="password" placeholder="**********" class="form-control" id="senha" name="senha" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="new_user" value="1"/>
                                <button class="btn btn-primary" type="submit">Salvar</button>
                                <a href="<?php bloginfo('url');?>/escolas" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <?php } else { 
        wp_redirect( bloginfo('url') . '/entrar' ); 
    } ?>

    </main>

<?php get_footer(); ?>