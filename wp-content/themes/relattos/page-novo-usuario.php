<?php get_header(); 
    if( !session_id() )
	{
	   session_start();
  	}
    ?>
    <main class="d-flex flex-nowrap">

    <?php include ('inc/menu.php'); 
    include (TEMPLATEPATH . '/inc/class.messages.php' ); 
    $msg = new Messages();
    if(isset($_POST['new_user'])){        
        extract($_POST);
        $name = explode(" ", $nome);
        $firstname = $name[0];
        $lastname = $name[1];
        if(current_user_can('administrator')){
            if($funcao == 'diretor'){
                $role = 'editor';
            }else{
                $role = 'subscriber';
            }
        }else{
            $role = 'subscriber';
        }
        $verifica = get_user_by( 'email', $email );
        if(!empty($verifica)){
            $msg->add('e', 'Já existe esse e-mail!');	
        }
        $id_user = wp_insert_user( array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => ucfirst($firstname),
            'last_name' => ucfirst($lastname),
            'display_name' => ucfirst($nome),
            'role' => $role
        ));
        $custom_usuario_logado= get_user_meta(get_current_user_id(),'escola');
        update_user_meta( $id_user, 'escola',$custom_usuario_logado[0] );
        $msg->add('s', 'Usuário cadastrado com sucesso!',home_url() . '/usuarios');	
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
                            <h2 class="font-24 pb-2">Adicionar <?php echo (current_user_can('administrator')) ? 'Usuário' : 'Membro'; ?></h2>
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
                                <label class="fw-bold color-secondary mb-2" for="nome">Nome</label>
                                <input class="form-control" type="text" id="nome" placeholder="Informe o Nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="email">E-mail</label>
                                <input class="form-control" type="email" id="email" placeholder="Informe o Email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="senha">Senha</label>
                                <input class="form-control" type="password" placeholder="******" id="senha" name="senha" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="funcao">Função</label>
                                <select class="form-select" id="funcao" name="funcao" required>
                                    <option value="">Selecione</option>
                                    <option value="coordenador">Coordenador</option>
                                <?php 
                                    if(current_user_can('administrator')){
                                    ?>
                                    <option value="diretor">Diretor</option>
                                <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="new_user" value="1"/>
                                <button class="btn btn-primary" type="submit">Salvar</button>
                                <a class="btn btn-outline-secondary" href="<?php bloginfo('url'); ?>/equipe">Cancelar</a>
                            </div>
                        </div>                
                    </form>
                </div>

            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>