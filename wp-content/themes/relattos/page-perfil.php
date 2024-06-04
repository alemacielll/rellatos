<?php get_header(); 
    if( !session_id() )
	{
	   session_start();
  	}
    include (TEMPLATEPATH . '/inc/class.messages.php' ); 
    $msg = new Messages();
    ?>
    <main class="d-flex flex-nowrap">
        
    <?php if ( is_user_logged_in() ) { 
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
            'posts_per_page' => '30',
            'paged'          => $paged,
            'post_type' => 'escola'
        );
        $wp_query = new WP_Query($args);

        if(isset($_POST['update_user'])){        
            extract($_POST);
            $name = explode(" ", $nome);
            $firstname = $name[0];
            $lastname = $name[1];            
            
            $verifica = get_userdata( get_current_user_id()); 
            if(!empty($verifica) && $email!=$verifica->data->user_email){
                $msg->add('e', 'Já existe esse e-mail!');	
            }
            if(!empty($senha)){
                wp_update_user( array(
                    'ID'=>get_current_user_id(),
                    'user_login' => $email,
                    'user_pass' => $senha,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstname),
                    'last_name' => ucfirst($lastname),
                    'display_name' => ucfirst($nome),
                    'role' => $role
                ));
            }else{
                wp_update_user( array(
                    'ID'=>get_current_user_id(),
                    'user_login' => $email,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstname),
                    'last_name' => ucfirst($lastname),
                    'display_name' => ucfirst($nome),
                    'role' => $role
                ));
            }
            
            $msg->add('s', 'Usuário atualizado com sucesso!');	
        }
        $user = get_user_by( 'ID', get_current_user_id() );
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
                                <h2 class="font-24 pb-2">Perfil</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                    <form method="post" action="" class="validate">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="nome">Nome</label>
                                <input class="form-control" type="text" id="nome" placeholder="Informe o Nome" name="nome" value="<?php echo (!empty($user))?$user->data->display_name:'';?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="email">E-mail</label>
                                <input class="form-control" type="email" id="email" placeholder="Informe o Email" name="email" value="<?php echo (!empty($user))?$user->data->user_email:'';?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="senha">Senha</label>
                                <input class="form-control" type="password" placeholder="******" id="senha" name="senha">
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="update_user" value="<?php echo get_current_user_id();?>"/>
                                <button class="btn btn-primary" type="submit">Enviar</button>
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