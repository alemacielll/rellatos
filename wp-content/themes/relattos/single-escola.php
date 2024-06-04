<?php 
    if( !session_id() )
	{
	   session_start();
  	}
    get_header(); 
    
    ?>
    <main class="d-flex flex-nowrap">
    <?php if ( is_user_logged_in()  && current_user_can('administrator')) { ?>
    <?php include ('inc/menu.php'); 
    include (TEMPLATEPATH . '/inc/class.messages.php' ); 
    $msg = new Messages();
    $diretor_old = get_field('diretor');
    if(isset($_POST['id_post'])){      
        extract($_POST);
        if(empty($id_novo_diretor)){
            $name = explode(" ", $nome);
            $firstname = $name[0];
            $lastname = $name[1];        
            $verifica = get_userdata( get_field('diretor')); 
            if(!empty($verifica) && $email!=$verifica->data->user_email){
                if($verifica->data->ID != get_field('diretor')){
                    $msg->add('e', 'Já existe esse e-mail!',home_url() . '/?post_type=escola&p=' . $id_post);	
                }
            }
            if(!empty($senha)){
                $id_user = wp_update_user( array(
                    'ID' => get_field('diretor'),
                    'user_login' => $email,
                    'user_pass' => $senha,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstname),
                    'last_name' => ucfirst($lastname),
                    'display_name' => ucfirst($nome)
                ));
            }else{
                $id_user = wp_update_user( array(
                    'ID' => get_field('diretor'),
                    'user_login' => $email,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstname),
                    'last_name' => ucfirst($lastname),
                    'display_name' => ucfirst($nome)
                ));
            }
        }
        $new_post = array(
            'ID' => $id_post,
            'post_type' => 'escola',
            'post_title' => $escola_nome
        );
        wp_update_post($new_post);
        update_post_meta($id_post, 'tipo_escola', $tipo_escola);   
        if(!empty($id_novo_diretor)){
            update_post_meta($id_post, 'diretor', $id_novo_diretor); 
            delete_user_meta( $diretor_old, 'escola',''); 
            update_user_meta( $id_novo_diretor, 'escola',$id_post );
        }     
        $msg->add('s', 'Escola atualizada com sucesso!',home_url() . '/escolas');
       
    }
    $diretor = get_field('diretor');
    $diretor = get_userdata( $diretor );
    $usuarios = get_users(array('role__in' => array('editor')));
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
                            <h2 class="font-24 pb-2">Editar Escola</h2>
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
                                <input type="text" placeholder="Informe o nome" class="form-control" id="escola_nome" name="escola_nome" required value="<?php the_title();?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="col-md-12">
                                    <label class="fw-bold color-secondary mb-2" for="escola_nome">Tipo Escola</label>
                                </div>
                                <div class="form-check ps-0 form-check-inline">
                                    <input type="radio" id="estadual" name="tipo_escola" value="estadual" <?php echo (get_field('tipo_escola')=='estadual')?'checked':'';?>>
                                    <label for="estadual">Estadual</label><br>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="municipal" name="tipo_escola" value="municipal" <?php echo (get_field('tipo_escola')=='municipal')?'checked':'';?>>
                                    <label for="municipal">Municipal</label><br>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="nome">Nome Diretor</label>
                                <input type="text" placeholder="Informe o nome" class="form-control" id="nome" name="nome" required value="<?php echo (!empty($diretor))?$diretor->data->display_name:'';?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="email">E-mail</label>
                                <input type="email" placeholder="Informe o E-mail" class="form-control" id="email" name="email" required value="<?php echo (!empty($diretor))?$diretor->data->user_email:'';?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold color-secondary mb-2" for="senha">Senha</label>
                                <input type="password" placeholder="**********" class="form-control" id="senha" name="senha" >
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="fw-bold color-secondary mb-2" for="id_novo_diretor">Trocar diretor</label>
                                <select class="form-select" id="id_novo_diretor" name="id_novo_diretor">
                                    <option value="">Selecione</option>
                                <?php 
                                    foreach($usuarios as $usuario){
                                        if($usuario->data->ID != $diretor->data->ID){
                                    ?>
                                    <option value="<?php echo $usuario->data->ID;?>"><?php echo $usuario->data->display_name; ?></option>
                                <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="id_post" value="<?php the_ID();?>"/>
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