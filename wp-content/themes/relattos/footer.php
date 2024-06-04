	</main>
	
	<?php wp_footer(); ?>
	
	
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/Js_utils.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript">
	
		
		jQuery(document).ready(function () {
			$('.validate').validate();
			$('.mascara_telefone').mask("(99) 9999-9999?9").keydown(function () {
				var $elem = $(this);
				var tamanhoAnterior = this.value.replace(/\D/g, '').length;
				setTimeout(function () {
					var novoTamanho = $elem.val().replace(/\D/g, '').length;
					if (novoTamanho !== tamanhoAnterior) {
						if (novoTamanho === 11) {
							$elem.unmask();
							$elem.mask("(99) 99999-9999");
						} else if (novoTamanho === 10) {
							$elem.unmask();
							$elem.mask("(99) 9999-9999?9");
						}
					}
				}, 1);
			});
			$('.mascara_data').mask('99/99/9999');
			$('.mascara_cep').mask('99999-999');
			$('.mascara_cpf').mask('999.999.999-99');
		});
	</script>

	</body>
</html>