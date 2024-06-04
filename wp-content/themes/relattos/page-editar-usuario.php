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
    $user = get_user_by( 'ID', $_GET['id'] );
    $custom_usuario_logado= get_user_meta(get_current_user_id(),'escola');
    $custom_usario= get_user_meta($_GET['id'],'escola');
    $usuario_custom = get_userdata( $_GET['id']); 
    if(empty($user) || ($custom_usario[0] != $custom_usuario_logado[0] && !current_user_can('administrator'))){
        $msg->add('s', 'Usuário não existe!');	
        wp_redirect(home_url() . '/usuarios');
    }
    if(isset($_POST['update_user'])){        
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
        
        $verifica = get_userdata( $_GET['id']); 
        if(!empty($verifica) && $email!=$verifica->data->user_email){
            $msg->add('e', 'Já existe esse e-mail!');	
        }
        if(!empty($senha)){
            wp_update_user( array(
                'ID'=>$update_user,
                'user_pass' => $senha,
                'user_login' => $email,
                'user_email' => $email,
                'first_name' => ucfirst($firstname),
                'last_name' => ucfirst($lastname),
                'display_name' => ucfirst($nome),
                'role' => $role
            ));
        }else{
            wp_update_user( array(
                'ID'=>$update_user,
                'user_login' => $email,
                'user_email' => $email,
                'first_name' => ucfirst($firstname),
                'last_name' => ucfirst($lastname),
                'display_name' => ucfirst($nome),
                'role' => $role
            ));
        }
        
        $msg->add('s', 'Usuário atualizado com sucesso!',home_url() . '/usuarios');	
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
                            <h2 class="font-24 pb-2">Adicionar Usuário</h2>
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
                                <input class="form-control" type="text" id="nome" placeholder="Informe o Nome" name="nome" value="<?php echo (!empty($user))?$user->data->display_name:'';?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="email">E-mail</label>
                                <input class="form-control" type="email" id="email" placeholder="Informe o Email" name="email" value="<?php echo (!empty($user))?$user->data->user_email:'';?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="funcao">Função</label>
                                <select class="form-select" id="funcao" name="funcao" required>
                                    <option value="">Selecione</option>
                                    <option value="coordenador" <?php echo (!empty($usuario_custom) && $usuario_custom->roles[0]=='subscriber')?'selected':'';?>>Coordenador</option>
                                <?php 
                                    if(current_user_can('administrator')){
                                    ?>
                                    <option value="diretor" <?php echo (!empty($usuario_custom) && $usuario_custom->roles[0]=='editor')?'selected':'';?>>Diretor</option>
                                <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php 
                            if(current_user_can('editor')){
                            ?>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="senha">Senha</label>
                                <input class="form-control" type="password" placeholder="******" id="senha" name="senha">
                            </div>
                        <?php 
                            }
                            ?>
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="update_user" value="<?php echo $_GET['id'];?>"/>
                                <button class="btn btn-primary" type="submit">Enviar</button>
                            </div>
                        </div>                
                    </form>
                </div>

            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>