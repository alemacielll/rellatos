<?php
if (!session_id()) {
    session_start();
}
include(TEMPLATEPATH . '/inc/class.messages.php');
$msg = new Messages();
get_header(); ?>
<main class="d-flex flex-nowrap">

    <?php if (is_user_logged_in()) { ?>
        <?php include('inc/menu.php');
        $custom = get_user_meta(get_current_user_id(), 'escola');
        $args = array(
            'meta_key'     => 'escola',
            'meta_value'   => $custom[0],
            'meta_compare' => '=',
        );
        $user_query = new WP_User_Query($args);
        $usuarios = $user_query->get_results();

        if (isset($_POST['finalizar'])) {
            $escola = get_user_meta(get_current_user_id(), 'escola');
            extract($_POST);
            $new_post = array(
                'ID' => '',
                'post_date' => implode("-", array_reverse(explode("/", $post_date))),
                'post_type' => 'post',
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_category' => array($post_category),
                'post_status' => 'publish'
            );
            $post_id = wp_insert_post($new_post);
            add_post_meta($post_id, 'turma', $turma);
            add_post_meta($post_id, 'turno', $turno);
            add_post_meta($post_id, 'escola', $escola[0]);
            if ($post_category == 1) {
                add_post_meta($post_id, 'responsavel_atendimento', $responsavel_atendimento);
                add_post_meta($post_id, 'responsavel', $responsavel);
                add_post_meta($post_id, 'procurou', $procurou);
                add_post_meta($post_id, 'motivacao', $motivacao);
            } else if ($post_category == 2) {
                add_post_meta($post_id, 'servidores_participantes', explode(",", $servidores_participantes), true);
                add_post_meta($post_id, 'responsavel_nome', $responsavel_nome);
                add_post_meta($post_id, 'responsavel_cpf', $responsavel_cpf);
                add_post_meta($post_id, 'responsavel_endereco', $responsavel_endereco);
                add_post_meta($post_id, 'responsavel_contato', $responsavel_contato);
                add_post_meta($post_id, 'informacoes_relevantes', $informacoes_relevantes);
            } else if ($post_category == 3 || $post_category == 4 || $post_category == 5 || $post_category == 6) {
                $artigos_1 = explode("||", $artigos_1);
                if (!empty($artigos_1)) {
                    foreach ($artigos_1 as $key => $value) {
                        add_row('artigos_1', array('titulo'   => $value), $post_id);
                    }
                }
                $artigos_2 = explode("||", $artigos_2);
                if (!empty($artigos_2)) {
                    foreach ($artigos_2 as $key => $value) {
                        add_row('artigos_2', array('titulo'   => $value), $post_id);
                    }
                }
                $acoes = explode("||", $acoes);
                if (!empty($acoes)) {
                    foreach ($acoes as $key => $value) {
                        add_row('acoes', array('titulo'   => $value), $post_id);
                    }
                }
            }

            add_post_meta($post_id, 'tipo_de_notificacao_do_responsavel', $tipo_de_notificacao_do_responsavel);
            add_post_meta($post_id, 'acao_responsavel', $acao_responsavel);
            add_post_meta($post_id, 'nome_do_coordenador_ou_diretor', $nome_do_coordenador_ou_diretor);
            if ($post_id) {
                $msg->add('s', 'Ata cadastrada com sucesso!', home_url() . '/atas');
            } else {
                $msg->add('e', 'Erro ao tentar cadastrar!');
            }
        }
        $custom = get_user_meta(get_current_user_id(), 'escola');
        $args = array(
            'post_type' => 'escola',
            'p' => $custom[0]
        );

        $wp_query = new WP_Query($args);
        if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();
                $i++;
                $turnos = get_field('turno');
                $turmas_fundamental = get_field('turmas_fundamental');
                $turmas_medio = get_field('turmas_medio');
            endwhile;
        else :
            $msg->add('e', 'Escola indisponível!');
        endif;

        if (empty($turnos) || empty($turmas_fundamental) || empty($turmas_medio)) {
            $msg->add('e', 'Escola com cadastro incompleto!', home_url() . '/atas');
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
                            <div class="col-md-12 text-center">
                                <h2 class="font-24 pb-2">Adicionar Ata</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <?php /*
                    <ul class="steps">
                        <!-- <li class="step step-active">
                        <li class="step step-success"> -->
                        <li class="step step-active">
                            <div class="step-content">
                                <span class="step-circle">1</span>
                                <!-- <span class="step-text">Etapa 1</span> -->
                            </div>
                        </li>
                        <li class="step">
                            <div class="step-content">
                                <span class="step-circle">2</span>
                                <!-- <span class="step-text">Etapa 2</span> -->
                            </div>
                        </li>
                        <li class="step">
                            <div class="step-content">
                                <span class="step-circle">3</span>
                                <!-- <span class="step-text">Etapa 3</span> -->
                            </div>
                        </li>
                        <li class="step">
                            <div class="step-content">
                                <span class="step-circle">4</span>
                                <!-- <span class="step-text">Etapa 4</span> -->
                            </div>
                        </li>
                    </ul>
                    */ ?>

                        <div class="col-md-8 offset-2 mt-3">
                            <?php
                            if ($_POST['step'] == '1' || empty($_POST['step'])) {
                            ?>
                                <form class="validate" method="post">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="fw-bold color-secondary mb-2">Data <span class="text-danger">*</span></label>
                                            <br />
                                            <span>Esta data deve ser a do dia em que ocorreu O REGISTRO, a data do ocorrido deve ser mencionada nos relatos.</span>
                                            <input placeholder="___/___/___" class="form-control mascara_data" type="data" name="post_date" value="<?php echo current_time('d/m/Y'); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold color-secondary mb-2">Turma <span class="text-danger">*</span></label>
                                            <select class="form-select" name="turma" required>
                                                <option value="">Escolher</option>
                                                <?php
                                                for ($i = 1; $i <= 9; $i++) {
                                                    for ($letra = 'A'; $letra <= 'F'; $letra++) {
                                                        if (in_array($i . '_' . strtolower($letra), $turmas_fundamental)) {
                                                ?>
                                                            <option value="EF <?php echo $i; ?>º <?php echo $letra; ?>">Ensino Fundamental <?php echo $i; ?>º <?php echo $letra; ?></option>
                                                        <?php
                                                        }
                                                    }
                                                }

                                                for ($i = 1; $i <= 3; $i++) {
                                                    for ($letra = 'A'; $letra <= 'F'; $letra++) {
                                                        if (in_array($i . '_' . strtolower($letra), $turmas_medio)) {
                                                        ?>
                                                            <option value="EM <?php echo $i; ?>ª <?php echo $letra; ?>">Ensino Médio <?php echo $i; ?>ª <?php echo $letra; ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold color-secondary mb-2">Turno <span class="text-danger">*</span></label>
                                            <select class="form-select" name="turno" required>
                                                <option value="">Escolher</option>
                                                <?php
                                                foreach ($turnos as $dados) {
                                                ?>
                                                    <option value="<?php echo $dados; ?>"><?php echo ucfirst($dados); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="fw-bold color-secondary mb-2">Nome Completo do Aluno <span class="text-danger">*</span></label>
                                            <br />
                                            <span>Para padronização e destaque nos documentos, inserir o nome completo com todas as letras maiúscula.</span>
                                            <input placeholder="Informe o Nome" class="form-control text-uppercase" type="text" name="post_title" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="fw-bold color-secondary mb-2">Ações <span class="text-danger">*</span></label>
                                        </div>
                                        <?php
                                        $categorias = get_terms(array(
                                            'taxonomy' => 'category',
                                            'hide_empty' => false,
                                        ));
                                        ?>
                                        <?php foreach ($categorias as $categoria) {
                                        ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="post_category" id="cat-<?php echo $categoria->term_id; ?>" value="<?php echo $categoria->term_id; ?>" required>
                                                    <label class="form-check-label" for="cat-<?php echo $categoria->term_id; ?>"><?php echo $categoria->name; ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-12 text-center mt-3">
                                            <input type="hidden" name="step" value="2">
                                            <input type="submit" class="btn btn-primary btn-sample" value="Próximo" name="">
                                        </div>
                                </form>
                            <?php
                            }
                            if ($_POST['step'] == '2') {
                            ?>
                                <form class="validate" method="post">
                                    <div class="row">
                                        <?php
                                        //print_r($_POST['post_category']);die;
                                        if ($_POST['post_category'] == 1) {
                                        ?>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2">Responsável pelo atendimento <span class="text-danger">*</span></label>
                                                <br />
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="responsavel_atendimento" id="direcao" value="Direção" required>
                                                    <label class="form-check-label" for="direcao">Direção</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="responsavel_atendimento" id="coordenacao" value="Coordenação" required>
                                                    <label class="form-check-label" for="coordenacao">Coordenação</label>
                                                </div>

                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2">Nome do Responsável ou familiar <span class="text-danger">*</span></label>
                                                <input placeholder="digite o nome" class="form-control text-uppercase" type="text" name="responsavel" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2">Procurou ou foi Convocado? <span class="text-danger">*</span></label>
                                                <br />
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="procurou" id="Procurou" value="Procurou" required>
                                                    <label class="form-check-label" for="Procurou">Procurou</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="procurou" id="convocado" value="Foi convocado" required>
                                                    <label class="form-check-label" for="convocado">Foi convocado</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2">Motivação <span class="text-danger">*</span></label>
                                                <br />
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="motivacao" id="Desempenho" value="Desempenho" required>
                                                    <label class="form-check-label" for="Desempenho">
                                                        Desempenho
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="motivacao" id="Disciplina" value="Disciplina" required>
                                                    <label class="form-check-label" for="Disciplina">
                                                        Disciplina
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="motivacao" id="Atitude" value="Atitude" required>
                                                    <label class="form-check-label" for="Atitude">
                                                        Atitude
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="motivacao" id="Ocorridos" value="Ocorridos" required>
                                                    <label class="form-check-label" for="Ocorridos">
                                                        Ocorridos
                                                    </label>
                                                </div>
                                            </div>
                                        <?php
                                        } else if ($_POST['post_category'] == 2) {
                                        ?>

                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2">Servidores Participantes <span class="text-danger">*</span></label>
                                                <br />
                                                <div class="row">
                                                    <?php
                                                    foreach ($usuarios as $usuario) {
                                                    ?>
                                                        <div class="form-check form-check-inline ">
                                                            <input class="form-check-input" type="checkbox" name="servidores_participantes[]" id="servidor_<?php echo $usuario->data->ID; ?>" value="<?php echo $usuario->data->ID; ?>" required>
                                                            <label class="form-check-label" for="servidor_<?php echo $usuario->data->ID; ?>"><?php echo $usuario->data->display_name; ?></label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2" for="responsavel_nome">Responsáveis <span class="text-danger">*</span></label>
                                                <input placeholder="digite o nome" id="responsavel_nome" class="form-control text-uppercase" type="text" name="responsavel_nome" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2" for="responsavel_cpf">CPF do Responsável <span class="text-danger">*</span></label>
                                                <input placeholder="digite seu cpf" id="responsavel_cpf" class="form-control text-uppercase mascara_cpf" type="cpf" name="responsavel_cpf" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2" for="responsavel_endereco">Endereço completo <span class="text-danger">*</span></label>
                                                <input placeholder="digite o endereço completo" class="form-control text-uppercase" type="text" name="responsavel_endereco" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2" for="responsavel_contato">Contato do Responsável <span class="text-danger">*</span></label>
                                                <input placeholder="contato do responsável" id="responsavel_contato" class="form-control text-uppercase mascara_telefone" type="text" name="responsavel_contato" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold color-secondary mb-2" for="informacoes_relevantes">Informações Relevantes <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="informacoes_relevantes" placeholder="insira aqui as informações relevantes e pertinentes ao estudante" id="informacoes_relevantes" rows="3"></textarea>
                                            </div>
                                            <?php

                                        } else if ($_POST['post_category'] == 3 || $_POST['post_category'] == 4 || $_POST['post_category'] == 5 || $_POST['post_category'] == 6) {
                                            $id_artigo = 0;
                                            if ($_POST['post_category'] == 3) {
                                                $id_artigo = 230;
                                            } else if ($_POST['post_category'] == 4) {
                                                $id_artigo = 231;
                                            } else if ($_POST['post_category'] == 5) {
                                                $id_artigo = 232;
                                            } else if ($_POST['post_category'] == 6) {
                                                $id_artigo = 233;
                                            }

                                            $args = array(
                                                'post_type' => 'artigo',
                                                'p' => $id_artigo
                                            );

                                            $wp_query = new WP_Query($args);
                                            if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();
                                                    $i++;
                                                    $artigos_1 = get_field('artigos_1');
                                                    $artigos_2 = get_field('artigos_2');
                                                    $acoes = get_field('acoes');
                                            ?>
                                                    <h2 class="font-24 fw-bold mb-2"><?php the_field('titulo_artigo_1'); ?></h2>
                                                    <p><?php the_field('texto_artigo_1'); ?></p>
                                                    <?php
                                                    foreach ($artigos_1 as $key => $artigo) {
                                                    ?>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" name="artigos_1[]" value="<?php echo $artigo['titulo']; ?>" id="artigo_1_<?php echo $key; ?>">
                                                            <label class="form-check-label" for="artigo_1_<?php echo $key; ?>">
                                                                <?php echo $artigo['titulo']; ?>
                                                            </label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <p></p>
                                                    <h2 class="font-24 fw-bold mb-2"><?php the_field('titulo_artigo_2'); ?></h2>
                                                    <p><?php the_field('texto_artigo_2'); ?></p>
                                                    <?php
                                                    foreach ($artigos_2 as $key => $artigo) {
                                                    ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="artigos_2[]" value="<?php echo $artigo['titulo']; ?>" id="artigo_2_<?php echo $key; ?>">
                                                            <label class="form-check-label" for="artigo_2_<?php echo $key; ?>">
                                                                <?php echo $artigo['titulo']; ?>
                                                            </label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <h2 class="font-24 fw-bold mb-2"><?php the_field('titulo_acoes'); ?></h2>
                                                    <p><?php the_field('texto_acoes'); ?></p>
                                                    <?php
                                                    foreach ($acoes as $key => $acao) {
                                                    ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="acoes[]" value="<?php echo $acao['titulo']; ?>" id="acao_<?php echo $key; ?>">
                                                            <label class="form-check-label" for="acao_<?php echo $key; ?>">
                                                                <?php echo $acao['titulo']; ?>
                                                            </label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                        <?php
                                                endwhile;
                                            else :
                                            endif;
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 text-center mt-3">
                                        <input type="hidden" name="post_date" value="<?php echo $_POST['post_date']; ?>">
                                        <input type="hidden" name="turma" value="<?php echo $_POST['turma']; ?>">
                                        <input type="hidden" name="turno" value="<?php echo $_POST['turno']; ?>">
                                        <input type="hidden" name="post_title" value="<?php echo $_POST['post_title']; ?>">
                                        <input type="hidden" name="post_category" value="<?php echo $_POST['post_category']; ?>">
                                        <input type="hidden" name="step" value="3">
                                        <a class="btn btn-outline-secondary me-2" href="javascript:history.back()">Anterior</a>
                                        <input type="submit" class="btn btn-primary btn-sample" value="Próximo" name="">
                                    </div>
                                </form>
                            <?php
                            }
                            if ($_POST['step'] == '3') {
                            ?>
                                <form class="validate" method="post">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="fw-bold color-secondary mb-2" for="post_content">Relatos <span class="text-danger">*</span></label>
                                            <textarea style="height: 270px;" class="form-control" id="post_content" name="post_content" rows="4" cols="50" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center mt-3">
                                        <input type="hidden" name="post_date" value="<?php echo $_POST['post_date']; ?>">
                                        <input type="hidden" name="turma" value="<?php echo $_POST['turma']; ?>">
                                        <input type="hidden" name="turno" value="<?php echo $_POST['turno']; ?>">
                                        <input type="hidden" name="post_title" value="<?php echo $_POST['post_title']; ?>">
                                        <input type="hidden" name="post_category" value="<?php echo $_POST['post_category']; ?>">
                                        <?php
                                        if ($_POST['post_category'] == 1) {
                                        ?>
                                            <input type="hidden" name="responsavel_atendimento" value="<?php echo $_POST['responsavel_atendimento']; ?>">
                                            <input type="hidden" name="responsavel" value="<?php echo $_POST['responsavel']; ?>">
                                            <input type="hidden" name="procurou" value="<?php echo $_POST['procurou']; ?>">
                                            <input type="hidden" name="motivacao" value="<?php echo $_POST['motivacao']; ?>">
                                        <?php
                                        } else if ($_POST['post_category'] == 2) {
                                        ?>
                                            <input type="hidden" name="servidores_participantes" value="<?php echo implode(",", $_POST['servidores_participantes']); ?>">
                                            <input type="hidden" name="responsavel_nome" value="<?php echo $_POST['responsavel_nome']; ?>">
                                            <input type="hidden" name="responsavel_cpf" value="<?php echo $_POST['responsavel_cpf']; ?>">
                                            <input type="hidden" name="responsavel_endereco" value="<?php echo $_POST['responsavel_endereco']; ?>">
                                            <input type="hidden" name="responsavel_contato" value="<?php echo $_POST['responsavel_contato']; ?>">
                                            <input type="hidden" name="informacoes_relevantes" value="<?php echo $_POST['informacoes_relevantes']; ?>">
                                        <?php
                                        } else if ($_POST['post_category'] == 3 || $_POST['post_category'] == 4 || $_POST['post_category'] == 5 || $_POST['post_category'] == 6) {
                                        ?>
                                            <input type="hidden" name="artigos_1" value="<?php echo implode("||", $_POST['artigos_1']); ?>">
                                            <input type="hidden" name="artigos_2" value="<?php echo implode("||", $_POST['artigos_2']); ?>">
                                            <input type="hidden" name="acoes" value="<?php echo implode("||", $_POST['acoes']); ?>">
                                        <?php
                                        }
                                        ?>
                                        <input type="hidden" name="step" value="4">
                                        <a class="btn btn-outline-secondary me-2" href="javascript:history.back()">Anterior</a>
                                        <input type="submit" class="btn btn-primary btn-sample" value="Próximo" name="">
                                    </div>
                                </form>
                            <?php
                            }
                            if ($_POST['step'] == '4') {
                            ?>
                                <form class="validate" method="post">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h3>Notificação dos Responsáveis</h3>
                                            <p>Em casos de FALTA LEVE, os responsáveis serão notificados via WHATSAPP ou LIGAÇÃO sobre a infração e medidas adotadas. A notificação visa conscientizar os responsáveis e auxiliar na orientação do aluno. Após o recebimento, os responsáveis deverão confirmar pelo WhatsApp seu conhecimento e compromisso em acompanhar o estudante.</p>
                                            <p>Para FALTAS GRAVES ou GRAVÍSSIMAS, a notificação por WHATSAPP ou LIGAÇÃO sobre a infração e solicitará a presença de um responsável na escola. Esse comparecimento é crucial para um diálogo detalhado sobre o ocorrido, possibilitando um melhor acompanhamento do aluno. Escola e família, juntas, buscarão soluções para prevenir reincidências e assegurar o bem-estar e sucesso educacional do estudante.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold color-secondary mb-2">Tipo de Notificação do Responsável <span class="text-danger">*</span></label>
                                        <br /><span>Tipo de notificação que o pai vai receber</span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_de_notificacao_do_responsavel" id="convocacao-comparecimento" value="Convocação de comparecimento" required>
                                            <label class="form-check-label" for="convocacao-comparecimento">
                                                Convocação de comparecimento
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_de_notificacao_do_responsavel" id="notificacao-ciencia" value="Notificação de Ciência de Sanção" required>
                                            <label class="form-check-label" for="notificacao-ciencia">
                                                Notificação de Ciência de Sanção
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo_de_notificacao_do_responsavel" id="nao-aplica" value="Não se aplica" required>
                                            <label class="form-check-label" for="nao-aplica">
                                                Não se aplica
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="fw-bold color-secondary mb-2">Ação do Responsável <span class="text-danger">*</span></label>
                                        <br /><span>Ação que o responsável terá que realizar ao receber a notiicação</span>
                                        <input placeholder="Ação do Responsável" class="form-control text-uppercase" type="text" name="acao_responsavel" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold color-secondary mb-2" for="nome_do_coordenador_ou_diretor">Nome do Coordenador ou Diretor <span class="text-danger">*</span></label>
                                        <select class="form-select" name="nome_do_coordenador_ou_diretor" required>
                                            <option value="">Escolher</option>
                                            <?php
                                            foreach ($usuarios as $usuario) {
                                            ?>
                                                <option value="<?php echo $usuario->data->display_name; ?>"><?php echo $usuario->data->display_name; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 text-center mt-3">
                                        <input type="hidden" name="post_date" value="<?php echo $_POST['post_date']; ?>">
                                        <input type="hidden" name="turma" value="<?php echo $_POST['turma']; ?>">
                                        <input type="hidden" name="turno" value="<?php echo $_POST['turno']; ?>">
                                        <input type="hidden" name="post_title" value="<?php echo $_POST['post_title']; ?>">
                                        <input type="hidden" name="post_category" value="<?php echo $_POST['post_category']; ?>">

                                        <?php
                                        if ($_POST['post_category'] == 1) {
                                        ?>
                                            <input type="hidden" name="responsavel_atendimento" value="<?php echo $_POST['responsavel_atendimento']; ?>">
                                            <input type="hidden" name="responsavel" value="<?php echo $_POST['responsavel']; ?>">
                                            <input type="hidden" name="procurou" value="<?php echo $_POST['procurou']; ?>">
                                            <input type="hidden" name="motivacao" value="<?php echo $_POST['motivacao']; ?>">
                                        <?php
                                        } else if ($_POST['post_category'] == 2) {
                                        ?>
                                            <input type="hidden" name="servidores_participantes" value="<?php echo  $_POST['servidores_participantes']; ?>">
                                            <input type="hidden" name="responsavel_nome" value="<?php echo $_POST['responsavel_nome']; ?>">
                                            <input type="hidden" name="responsavel_cpf" value="<?php echo $_POST['responsavel_cpf']; ?>">
                                            <input type="hidden" name="responsavel_endereco" value="<?php echo $_POST['responsavel_endereco']; ?>">
                                            <input type="hidden" name="responsavel_contato" value="<?php echo $_POST['responsavel_contato']; ?>">
                                            <input type="hidden" name="informacoes_relevantes" value="<?php echo $_POST['informacoes_relevantes']; ?>">
                                        <?php
                                        } else if ($_POST['post_category'] == 3 || $_POST['post_category'] == 4 || $_POST['post_category'] == 5 || $_POST['post_category'] == 6) {
                                        ?>
                                            <input type="hidden" name="artigos_1" value="<?php echo  $_POST['artigos_1']; ?>">
                                            <input type="hidden" name="artigos_2" value="<?php echo $_POST['artigos_2']; ?>">
                                            <input type="hidden" name="acoes" value="<?php echo $_POST['acoes']; ?>">
                                        <?php
                                        }
                                        ?>

                                        <input type="hidden" name="post_content" value="<?php echo $_POST['post_content']; ?>">
                                        <input type="hidden" name="finalizar" value="1">
                                        <a class="btn btn-outline-secondary me-2" href="javascript:history.back()">Anterior</a>
                                        <input type="submit" class="btn btn-primary btn-sample" value="Finalizar" name="">
                                    </div>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php } else {
        wp_redirect(bloginfo('url') . '/entrar');
    } ?>

</main>

<?php get_footer(); ?>