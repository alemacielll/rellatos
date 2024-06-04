<?php 
	// Verifica se o ID está presente na URL
	if (isset($_GET['id']) && !empty($_GET['id'])) {

	$post_id = $_GET['id'];
	$post_author_id = get_post_field('post_author', $post_id);
	$user_info = get_userdata($post_author_id);
	if ($user_info) {
	    $author_name = $user_info->first_name;
	    $author_email = $user_info->user_email;
	}

	if ($post_author_id == get_current_user_id() || current_user_can('administrator') || current_user_can('editor')) {
	
	$aluno = get_the_title($post_id);
	$content = get_post_field('post_content', $post_id);
	$relato = get_the_content($post_id);
	$turma = get_field( "turma", $post_id );
	$turno = get_field( "turno", $post_id );

	$author_id = get_post_field('post_author', $post_id);
	$author_name = get_the_author_meta('display_name', $author_id);

	$categorias = get_the_category($post_id);
	if(!empty($categorias)){
		$categoria_id = $categorias[0]->cat_ID;
	}
	// Verifica se existem categorias associadas ao post
	$categoria = !empty($categorias) ? $categorias[0]->name : '';
	$servidores_participantes = get_field( "servidores_participantes", $post_id );
	$responsavel_nome = get_field( "responsavel_nome", $post_id );
	$responsavel_cpf = get_field( "responsavel_cpf", $post_id );
	$responsavel_endereco = get_field( "responsavel_endereco", $post_id );
	$responsavel_contato = get_field( "responsavel_contato", $post_id );
	$informacoes_relevantes = get_field( "informacoes_relevantes", $post_id );
	//add_post_meta($post_id, 'responsavel_atendimento', $responsavel_atendimento);
	//add_post_meta($post_id, 'responsavel', $responsavel);
	//add_post_meta($post_id, 'procurou', $procurou);
	//add_post_meta($post_id, 'motivacao', $motivacao);


	$responsavel = get_field( "responsavel", $post_id );
	$procurou = get_field( "procurou", $post_id );
	$motivacao = get_field( "motivacao", $post_id );
	$responsavel_atendimento = get_field( "responsavel_atendimento", $post_id );
	$nome_do_coordenador_ou_diretor = get_field( "nome_do_coordenador_ou_diretor", $post_id );
	$acao_responsavel = get_field( "acao_responsavel", $post_id );

	$escola = get_the_author_meta('escola', $author_id);

	$escola = get_the_title($escola);

	$data = get_the_date('d/m/Y',$post_id);

	$tipo_de_notificacao_do_responsavel = get_field( "tipo_de_notificacao_do_responsavel", $post_id );
	require('fpdf181/fpdf.php');

	class PDF extends FPDF {
    // Cabeçalho
	    function Header() {
	        if ($this->PageNo() > 1) {
	            // Adicionar a logo no cabeçalho
	            $urlLogo = 'https://argoclientes.pt/wp-content/themes/relattos/img/logo.png';
	            $larguraLogo = 16; // Largura da logo em mm
	            $alturaLogo = 7; // Altura da logo em mm
	            $this->Image($urlLogo, 10, 10, $larguraLogo, $alturaLogo);

	            // Adicionar uma linha abaixo do logo
	            $this->SetLineWidth(0.1);
	            $this->Line(10, 20, $this->GetPageWidth() - 10, 20);
	        }
	    }

	    // Rodapé
	    function Footer() {
	        // Verifica se não é a primeira página
	        if ($this->PageNo() > 1) {
	            // Posiciona-se a 1,5 cm do fim da página
	            $this->SetY(-15);
	            // Adiciona uma linha
	            $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
	            // Define a fonte para o rodapé
	            $this->SetFont('Arial', '', 8);

	            // Adiciona a data atual à esquerda
	            $this->Cell(0, 10, 'Impresso em: ' . date('d/m/Y'), 0, 0, 'L');


	            // Adiciona o número da página à direita
	            $this->Cell(0, 10, $this->PageNo(), 0, 0, 'R');
	        }
	    }
	}

	$pdf = new PDF('P','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 16);

	// Alturas e espaçamentos
	$alturaLogo = 9; // Altura da logo em mm
	$espacoEntreElementos = 10; // Espaço entre logo, título e texto adicional
	$alturaCelulaTitulo = 20; // Altura aproximada da célula do título
	$alturaCelulaTextoAdicional = 20; // Altura aproximada da célula do texto adicional

	// Calcular altura total dos elementos
	$alturaTotal = $alturaLogo + $espacoEntreElementos + $alturaCelulaTitulo + $espacoEntreElementos + $alturaCelulaTextoAdicional + 50;

	// Calcular ponto de início vertical
	$inicioVertical = ($pdf->GetPageHeight() - $alturaTotal) / 2;

	// Adicionar a logo
	$urlLogo = 'https://argoclientes.pt/wp-content/themes/relattos/img/logo.png';
	$larguraLogo = 40; // Largura da logo em mm
	$alturaLogo = 18;
	$x = ($pdf->GetPageWidth() - $larguraLogo) / 2; // Centralizar horizontalmente
	$pdf->Image($urlLogo, $x, $inicioVertical, $larguraLogo, $alturaLogo);

	// Cabeçalho
	$inicioVertical += $alturaLogo + $espacoEntreElementos; // Ajustar posição vertical para o título
	$texto = 'Relatório de Ocorrido em Sala de Aula';
	$texto = utf8_decode($texto);
	$pdf->SetTextColor(1, 90, 170);
	$pdf->SetY($inicioVertical);
	$pdf->Cell(0, $alturaCelulaTitulo, $texto, 0, 1, 'C');

	// Empresa
	$inicioVertical += $alturaCelulaTitulo + $espacoEntreElementos; // Ajustar posição vertical para o texto adicional
	$pdf->SetFont('Arial', '', 12); 
	$pdf->SetTextColor(0, 0, 0); 
	$pdf->SetY($inicioVertical);
	$pdf->Cell(0, $alturaCelulaTextoAdicional, utf8_decode($escola), 0, 1, 'C'); 
	$pdf->Cell(0, $alturaCelulaTextoAdicional, utf8_decode($aluno . ' - ' . $turma), 0, 1, 'C'); 

	// Ano
	$inicioVertical += $alturaCelulaTitulo - 12; // Ajustar posição vertical para o texto adicional
	$pdf->SetFont('Arial', '', 12); 
	$pdf->SetTextColor(0, 0, 0); 
	$pdf->SetY($inicioVertical);
	$pdf->Cell(0, $alturaCelulaTextoAdicional, utf8_decode($ano), 0, 1, 'C'); 
	$pdf->SetFont('Arial', '', 11); 

	// Segunda página
	$pdf->SetTopMargin(25);
	$pdf->AddPage();

	$pdf->SetTextColor(1, 90, 170); // Verde
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->Cell(0, 8, utf8_decode('Dados Gerais'), 0, 1);

	$pdf->SetTextColor(0, 0, 0); // Preto
	$pdf->SetFont('Arial', '', 12);

	$pdf->Ln(5);
	
	$pdf->MultiCell(0, 6, utf8_decode('Aluno: ' . $aluno), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode('Data:' . $data), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode('Turma: ' . $turma), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode('Turno: ' . $turno), 0, 1);
	
	$pdf->MultiCell(0, 6, utf8_decode('Ações: ' . $categoria), 0, 1);
	//print_r($categoria_id);die;
	if($categoria_id==2){
		$pdf->MultiCell(0, 6, utf8_decode('Servidores Participantes: '), 0, 1);
		foreach($servidores_participantes as $servidor){
			$nome = get_user_by('id',$servidor);
			$pdf->MultiCell(0, 6, utf8_decode($nome->data->display_name), 0, 1);
		}
		$pdf->MultiCell(0, 6, utf8_decode('Responsáveis: ' . $responsavel_nome), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('CPF do Responsável: ' . $responsavel_cpf), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Endereço completo: ' . $responsavel_endereco), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Contato do Responsável: ' . $responsavel_contato), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Informações Relevantes: ' . $informacoes_relevantes), 0, 1);
	}else if($categoria_id==1){
		$pdf->MultiCell(0, 6, utf8_decode('Responsável pelo atendimento: '.$responsavel_atendimento), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Nome do Responsável ou familiar: '.$responsavel), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Procurou ou foi Convocado? '.$procurou), 0, 1);
		$pdf->MultiCell(0, 6, utf8_decode('Motivação: '.$motivacao), 0, 1);
	}else if($categoria_id==3 || $categoria_id==4 || $categoria_id==5 || $categoria_id==6){
		$id_artigo = 0;
		if($categoria_id==3){
			$id_artigo = 230;
		}else if($categoria_id==4){
			$id_artigo = 231;
		}else if($categoria_id==5){
			$id_artigo = 232;
		}
		else if($categoria_id==6){
			$id_artigo = 233;
		}
		$args = array(
			'post_type' => 'artigo',
			'p'=>$id_artigo
		);		
		$ata_artigos_1 = get_field( "artigos_1", $post_id );
		$ata_artigos_2 = get_field( "artigos_2", $post_id );
		$ata_acoes = get_field( "acoes", $post_id );
		
		$wp_query = new WP_Query($args);
		if($wp_query->have_posts()) : while($wp_query->have_posts()) : $wp_query->the_post(); $i++;
			if(!empty($ata_artigos_1)){
				$pdf->MultiCell(0, 6, utf8_decode(get_field('titulo_artigo_1')), 0, 1);
				foreach($ata_artigos_1 as $d){
					$pdf->MultiCell(0, 6, utf8_decode($d['titulo']), 0, 1);
				}
			}

			if(!empty($ata_artigos_2)){
				$pdf->MultiCell(0, 6, utf8_decode(get_field('titulo_artigo_2')), 0, 1);
				foreach($ata_artigos_2 as $d){
					$pdf->MultiCell(0, 6, utf8_decode($d['titulo']), 0, 1);
				}
			}

			if(!empty($ata_acoes)){
				$pdf->MultiCell(0, 6, utf8_decode(get_field('titulo_acoes')), 0, 1);
				
				foreach($ata_acoes as $d){
					$pdf->MultiCell(0, 6, utf8_decode($d['titulo']), 0, 1);
				}
			}
			
		endwhile; else :                                         
		endif;
		
	}
	$pdf->MultiCell(0, 6, utf8_decode('Tipo de notificação que o pai vai receber: ' . $tipo_de_notificacao_do_responsavel), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode('Ação que o responsável terá que realizar ao receber a notificação: ' . $acao_responsavel), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode('Nome do Coordenador ou Diretor: ' . $nome_do_coordenador_ou_diretor), 0, 1);	
	$pdf->Ln(10);

	$pdf->SetTextColor(1, 90, 170); // Verde
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->Cell(0, 8, utf8_decode('Relatos'), 0, 1);

	$pdf->SetTextColor(0, 0, 0); // Preto
	$pdf->SetFont('Arial', '', 12);

	$pdf->MultiCell(0, 6, utf8_decode($content), 0, 1);


	// linha em branco
	$pdf->Ln(10);

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->MultiCell(0, 6, utf8_decode('Campo Grande, ' . $data), 0, 1);
	$pdf->MultiCell(0, 6, utf8_decode($escola), 0, 1);

	$pdf->Output();


	} else {
        // O usuário não tem permissão
        // Você pode redirecionar, mostrar uma mensagem de erro, etc.
        echo "Você não tem permissão para acessar esses dados.";
    }


	} else {
	    // O ID não está presente ou é inválido
	    echo "ID não fornecido ou inválido.";
	}

?>