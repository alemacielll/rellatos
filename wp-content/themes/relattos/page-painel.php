<?php get_header(); 
if (current_user_can('editor') || current_user_can('subscriber')) { 
    $escola = get_user_meta(get_current_user_id(),'escola');
}
function sanitizeString($string) {

    // matriz de entrada
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

    // matriz de saída
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_' );

    // devolver a string
    return str_replace($what, $by, $string);
}
$escola = $escola[0];
$total_acoes = 0;
$total_turmas = 0;
$total_diretor = 0;
$total_coordenador = 0;
$acoes_painel = array();
$relatorio_turma_mat = array();
$relatorio_turma_ves = array();
$relatorio_turma_not = array();
$relatorio_diretor= array(
    'matutino'=>0,
    'vespertino'=>0,
    'noturno'=>0
);
$relatorio_coordenador= array(
    'matutino'=>0,
    'vespertino'=>0,
    'noturno'=>0
);
$cores = array(
    '1'=>'rgb(60, 232, 142)',
    '2'=> 'rgb(255, 165, 0)', 
    '3'=>'rgb(255, 0, 0)', 
    '4'=>'rgb(255, 255, 0)', 
    '5'=>'rgb(173, 216, 230)',
    '6' => 'rgb(255, 22, 167)'

);
wp_reset_query();
$args = array(
    'posts_per_page' => '-1',
    'post_type' => 'post',
);       
if (current_user_can('editor') || current_user_can('subscriber')) {                         
    $args['meta_key'] = "escola";
    $args['meta_value'] = $escola;
    $args['meta_compare'] = "=";   
} 

$wp_query = new WP_Query($args);

if ($wp_query->have_posts()) : 
    while ($wp_query->have_posts()) : $wp_query->the_post(); 
    $total_acoes++;
    $turma = get_field ('turma');
    $author_id = get_post_field ('post_author', get_the_ID());
    $roles = get_userdata($author_id);
    $roles = $roles->roles[0];
    if($roles == 'editor'){
        $total_diretor++;
    }else{
        $total_coordenador++;
    }
    $cat = get_the_category();
    if(!isset($acoes_painel[$cat[0]->term_id])){
        $acoes_painel[$cat[0]->term_id]['total']=0;
        $acoes_painel[$cat[0]->term_id]['titulo']=$cat[0]->cat_name;
        $acoes_painel[$cat[0]->term_id]['total_matutino']=0;
        $acoes_painel[$cat[0]->term_id]['total_vespertino']=0;
        $acoes_painel[$cat[0]->term_id]['total_noturno']=0;
    }
    $acoes_painel[$cat[0]->term_id]['total']++;
    if(get_field('turno')=='matutino'){
        $acoes_painel[$cat[0]->term_id]['total_matutino']++;
        if(!isset($relatorio_turma_mat[sanitizeString($turma)])){
            $relatorio_turma_mat[sanitizeString($turma)]['titulo']=$turma;
            $relatorio_turma_mat[sanitizeString($turma)]['total']=0;
            $total_turmas++;
        }
        $relatorio_turma_mat[sanitizeString($turma)]['total']++;
        if($roles == 'editor'){
            $relatorio_diretor['matutino']++;
        }else{
            $relatorio_coordenador['matutino']++;
        }

    }else if(get_field('turno')=='vespertino'){
        $acoes_painel[$cat[0]->term_id]['total_vespertino']++;
        if(!isset($relatorio_turma_ves[sanitizeString($turma)])){
            $relatorio_turma_ves[sanitizeString($turma)]['titulo']=$turma;
            $relatorio_turma_ves[sanitizeString($turma)]['total']=0;
            $total_turmas++;
        }
        $relatorio_turma_ves[sanitizeString($turma)]['total']++;
        if($roles == 'editor'){
            $relatorio_diretor['vespertino']++;
        }else{
            $relatorio_coordenador['vespertino']++;
        }
    }else if(get_field('turno')=='noturno'){
        $acoes_painel[$cat[0]->term_id]['total_noturno']++;
        if(!isset($relatorio_turma_not[sanitizeString($turma)])){
            $relatorio_turma_not[sanitizeString($turma)]['titulo']=$turma;
            $relatorio_turma_not[sanitizeString($turma)]['total']=0;
            $total_turmas++;
        }
        $relatorio_turma_not[sanitizeString($turma)]['total']++;
        if($roles == 'editor'){
            $relatorio_diretor['noturno']++;
        }else{
            $relatorio_coordenador['noturno']++;
        }
    }
    
   
    
endwhile; 
else : 
endif; 
wp_reset_query();
/*
$args = array(
    'posts_per_page' => '-1',
    'post_type' => 'escola',
    'p'=>$escola
);   
$wp_query = new WP_Query($args);
if ($wp_query->have_posts()) : 
    while ($wp_query->have_posts()) : $wp_query->the_post(); 
    $turmas_fundamental = get_field('turmas_fundamental');
    foreach($turmas_fundamental as $turma){
        $total_turmas++;
    }
    $turmas_medio = get_field('turmas_medio');
    foreach($turmas_medio as $turma){
        $total_turmas++;
    }
endwhile; 
else : 
endif; */
    ?>
    <main class="d-flex flex-nowrap">
        
    <?php if ( is_user_logged_in() ) { ?>
    
        <?php include ('inc/menu.php'); ?>

        <div class="flex-grow-1 scrollarea p-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h2 class="font-24 pb-2 border-bottom">Painel</h2>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-center">
                            <div class="card-body">
                                <h3 class="fw-600 mb-0 fw-bold color-light font-45"><?php echo $total_acoes;?></h3>
                                <span class="font-20 color-light color-light fw-600">Total de Ações</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-center">
                            <div class="card-body">
                                <h3 class="fw-600 mb-0 fw-bold color-light font-45"><?php echo $total_turmas;?></h3>
                                <span class="font-20 color-light fw-600">Turmas Penalizadas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-center">
                            <div class="card-body">
                                <h3 class="fw-600 mb-0 fw-bold color-light font-45"><?php echo $total_diretor;?></h3>
                                <span class="font-20 color-light fw-600">Autor Diretor</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-center">
                            <div class="card-body">
                                <h3 class="fw-600 mb-0 fw-bold color-light font-45"><?php echo $total_coordenador;?></h3>
                                <span class="font-20 color-light fw-600">Autor Coordenador</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="font-18 color-primary mb-0 fw-bold">Tipos de Ações (%)</h2>
                            </div>
                            <div class="card-body text-center">
                                <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                    <canvas id="chart-1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="font-18 color-primary mb-0 fw-bold">Ações por Turno</h2>
                            </div>
                            <div class="card-body text-center">
                                <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                    <canvas id="chart-2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="font-18 color-primary mb-0 fw-bold">Ações por Turma</h2>
                            </div>
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-md-12">
                                        <nav class="mb-3">
                                          <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-matutino-tab" data-bs-toggle="tab" data-bs-target="#nav-matutino" type="button" role="tab" aria-controls="nav-matutino" aria-selected="true">Matutino</button>
                                            <button class="nav-link" id="nav-vespertino-tab" data-bs-toggle="tab" data-bs-target="#nav-vespertino" type="button" role="tab" aria-controls="nav-vespertino" aria-selected="false">Vespertino</button>
                                            <button class="nav-link" id="nav-noturno-tab" data-bs-toggle="tab" data-bs-target="#nav-noturno" type="button" role="tab" aria-controls="nav-noturno" aria-selected="false">Noturno</button>
                                          </div>
                                        </nav>
                                    </div>
                                </div>
                                <div class="tab-content" id="nav-tabContent">
                                  <div class="tab-pane fade show active" id="nav-matutino" role="tabpanel" aria-labelledby="nav-matutino-tab">
                                      <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                        <canvas id="chart-3"></canvas>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="nav-vespertino" role="tabpanel" aria-labelledby="nav-vespertino-tab">
                                    <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                        <canvas id="chart-3-1"></canvas>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="nav-noturno" role="tabpanel" aria-labelledby="nav-noturno-tab">
                                    <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                        <canvas id="chart-3-2"></canvas>
                                    </div>
                                  </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="font-18 color-primary mb-0 fw-bold">Ações por Autor</h2>
                            </div>
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-md-12">
                                        <nav class="mb-3">
                                          <div class="nav nav-tabs" id="nav-tab2" role="tablist">
                                            <button class="nav-link active" id="nav-diretor-tab" data-bs-toggle="tab" data-bs-target="#nav-diretor" type="button" role="tab" aria-controls="nav-diretor" aria-selected="true">Diretor</button>
                                            <button class="nav-link" id="nav-cordenador-tab" data-bs-toggle="tab" data-bs-target="#nav-cordenador" type="button" role="tab" aria-controls="nav-cordenador" aria-selected="false">Cordenador</button>
                                          </div>
                                        </nav>
                                    </div>
                                </div>
                                <div class="tab-content" id="nav-tabContent2">
                                  <div class="tab-pane fade show active" id="nav-diretor" role="tabpanel" aria-labelledby="nav-diretor-tab">
                                      <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                        <canvas id="chart-4"></canvas>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="nav-cordenador" role="tabpanel" aria-labelledby="nav-cordenador-tab">
                                  <div class="wrapper" style="width: 100%; margin: 0 auto; height: 298px;">
                                        <canvas id="chart-4-1"></canvas>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { 
        wp_redirect( bloginfo('url') . '/entrar' ); 
    } ?>

    </main>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/Chart.bundle.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/custom.js"></script>
    <script type="text/javascript">
	<?php 
		
		if(!empty($acoes_painel)){
		?>
		var data_chart_1 = {
			labels: [
			<?php 
				foreach($acoes_painel as $acao){
				?>
				'<?php echo $acao['titulo'];?>',
			<?php 
				}
				?>
			],
			datasets: [
				{
					label: 'Tipos de Ações',
					data: [
                    <?php 
                        foreach($acoes_painel as $acao){
                        ?>
                        <?php echo ($acao['total']>0)?$acao['total']:'';?>,
                    <?php 
                        }
                        ?>],
					backgroundColor: [
                        <?php 
                        foreach($acoes_painel as $cor=>$acao){
                        ?>
                        '<?php echo $cores[$cor];?>',
                    <?php 
                        }
                        ?>
					],
					hoverOffset: 4
				},
			]
		};
        chart = new Chart('chart-1', {
			type: 'doughnut',
			data: data_chart_1,
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					display: true,
				}
			}
		});


        var data_chart_2 = {
            labels: [
                "MATUTINO","VESPERTINO","NOTURNO",],
            datasets: [
            <?php 
                foreach($acoes_painel as $cor=>$acao){
                ?>
                {	
                    label: '<?php echo $acao['titulo'];?>',
                    backgroundColor: [
                        <?php 
                        foreach($acoes_painel as $background){
                        ?>
                            '<?php echo $cores[$cor];?>',
                    <?php 
                        }
                        ?>
                    ],
                    borderColor: [
                        <?php 
                        foreach($acoes_painel as $background){
                        ?>
                            '<?php echo $cores[$cor];?>',
                    <?php 
                        }
                        ?>
                    ],
                    data: ["<?php echo ($acao['total_matutino']>0)?$acao['total_matutino']:'';?>","<?php echo ($acao['total_vespertino']>0)?$acao['total_vespertino']:'';?>","<?php echo ($acao['total_noturno']>0)?$acao['total_noturno']:'';?>"],
                },
            <?php 
                }
                ?>
            ]
        };
        chart = new Chart('chart-2', {
            type: 'bar',
            data: data_chart_2,
            options: {
                scales: {
                    xAxes: [{
                        stacked: false
                    }],
                    yAxes: [{
                        stacked: false
                    }],
                    beginAtZero: true
                },
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                    boxWidth: 0,
                }
            }
        });
	<?php 
		}
        if(!empty($relatorio_turma_mat)){
		?>
        var data_chart_3 = {
            labels: [
            <?php 
                foreach($relatorio_turma_mat as $turma){
                ?>
                "<?php echo $turma['titulo'];?>",
            <?php 
                }
                ?>],
            datasets: [
                {
                    label: 'Ações por turma',
                    backgroundColor: [
                    <?php 
                    $cor = 1;
                    foreach($relatorio_turma_mat as $background){
                    ?>
                    '<?php echo $cores[$cor];?>', 
                <?php 
                        $cor++;
                    }
                    ?>],
                    borderColor: [
                    <?php 
                    $cor = 1;
                    foreach($relatorio_turma_mat as $background){
                    ?>
                    '<?php echo $cores[$cor];?>', 
                <?php 
                    $cor++;
                    }
                    ?>],
                    data: [
                        <?php 
                        foreach($relatorio_turma_mat as $turma){
                        ?>
                        <?php echo ($turma['total']>0)?$turma['total']:'';?>,
                    <?php 
                        }
                        ?>],
                },
            ]
        };

        chart = new Chart('chart-3', {
            type: 'bar',
            data: data_chart_3,
            options: {
                scales: {
                    xAxes: [{
                        stacked: false
                    }],
                    yAxes: [{
                        stacked: false
                    }],
                },
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    position: 'bottom',
                    boxWidth: 0,
                }
            }
        });
    <?php 
        }if(!empty($relatorio_turma_ves)){
            ?>
            var data_chart_3_1 = {
                labels: [
                <?php 
                    foreach($relatorio_turma_ves as $turma){
                    ?>
                    "<?php echo $turma['titulo'];?>",
                <?php 
                    }
                    ?>],
                datasets: [
                    {
                        label: 'Ações por turma',
                        backgroundColor: [
                        <?php 
                        $cor = 1;
                        foreach($relatorio_turma_ves as $background){
                        ?>
                        '<?php echo $cores[$cor];?>', 
                    <?php 
                            $cor++;
                        }
                        ?>],
                        borderColor: [
                        <?php 
                        $cor = 1;
                        foreach($relatorio_turma_ves as $background){
                        ?>
                        '<?php echo $cores[$cor];?>', 
                    <?php 
                        $cor++;
                        }
                        ?>],
                        data: [
                            <?php 
                            foreach($relatorio_turma_ves as $turma){
                            ?>
                            <?php echo ($turma['total']>0)?$turma['total']:'';?>,
                        <?php 
                            }
                            ?>],
                    },
                ]
            };
    
            chart = new Chart('chart-3-1', {
                type: 'bar',
                data: data_chart_3_1,
                options: {
                    scales: {
                        xAxes: [{
                            stacked: false
                        }],
                        yAxes: [{
                            stacked: false
                        }],
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        position: 'bottom',
                        boxWidth: 0,
                    }
                }
            });
        <?php 
            }if(!empty($relatorio_turma_not)){
                ?>
                var data_chart_3_2 = {
                    labels: [
                    <?php 
                        foreach($relatorio_turma_not as $turma){
                        ?>
                        "<?php echo $turma['titulo'];?>",
                    <?php 
                        }
                        ?>],
                    datasets: [
                        {
                            label: 'Ações por turma',
                            backgroundColor: [
                            <?php 
                            $cor = 1;
                            foreach($relatorio_turma_not as $background){
                            ?>
                            '<?php echo $cores[$cor];?>', 
                        <?php 
                                $cor++;
                            }
                            ?>],
                            borderColor: [
                            <?php 
                            $cor = 1;
                            foreach($relatorio_turma_not as $background){
                            ?>
                            '<?php echo $cores[$cor];?>', 
                        <?php 
                            $cor++;
                            }
                            ?>],
                            data: [
                                <?php 
                                foreach($relatorio_turma_not as $turma){
                                ?>
                                <?php echo ($turma['total']>0)?$turma['total']:'';?>,
                            <?php 
                                }
                                ?>],
                        },
                    ]
                };
        
                chart = new Chart('chart-3-2', {
                    type: 'bar',
                    data: data_chart_3_2,
                    options: {
                        scales: {
                            xAxes: [{
                                stacked: false
                            }],
                            yAxes: [{
                                stacked: false
                            }],
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false,
                            position: 'bottom',
                            boxWidth: 0,
                        }
                    }
                });
            <?php 
                }
                if(!empty($relatorio_diretor)){
                ?>
                var data_chart_4 = {
                    labels: ["Matutino","Vespertino","Noturno",],
                    datasets: [
                        {
                            label: 'Ações por Autor',
                            backgroundColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)' ],
                            borderColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)' ],
                            data: [<?php echo ($relatorio_diretor['matutino']>0)?$relatorio_diretor['matutino']:'';?>,<?php echo ($relatorio_diretor['vespertino']>0)?$relatorio_diretor['vespertino']:'';?>,<?php echo ($relatorio_diretor['noturno']>0)?$relatorio_diretor['noturno']:'';?>],
                        },
                    ]
                };

                chart = new Chart('chart-4', {
                    type: 'bar',
                    data: data_chart_4,
                    options: {
                        scales: {
                            xAxes: [{
                                stacked: false
                            }],
                            yAxes: [{
                                stacked: false
                            }],
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false,
                            position: 'bottom',
                            boxWidth: 0,
                        }
                    }
                });
            <?php 
                }if(!empty($relatorio_coordenador)){
                    ?>
                    var data_chart_4_1 = {
                        labels: ["Matutino","Vespertino","Noturno",],
                        datasets: [
                            {
                                label: 'Ações por Autor',
                                backgroundColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)' ],
                                borderColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)' ],
                                data: [<?php echo ($relatorio_coordenador['matutino']>0)?$relatorio_coordenador['matutino']:'';?>,<?php echo ($relatorio_coordenador['vespertino']>0)?$relatorio_coordenador['vespertino']:'';?>,<?php echo ($relatorio_coordenador['noturno']>0)?$relatorio_coordenador['noturno']:'';?>],
                            },
                        ]
                    };
    
                    chart = new Chart('chart-4-1', {
                        type: 'bar',
                        data: data_chart_4_1,
                        options: {
                            scales: {
                                xAxes: [{
                                    stacked: false
                                }],
                                yAxes: [{
                                    stacked: false
                                }],
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false,
                                position: 'bottom',
                                boxWidth: 0,
                            }
                        }
                    });
                <?php 
                    }
                ?>
        </script>
<?php get_footer(); ?>