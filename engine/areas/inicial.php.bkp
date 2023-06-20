<? include "engine/html/topo.php";?>
	<div class="geral">
    <? include "engine/html/topo-site.php";?>
		<?php
		// slider
		$sql_slider = "SELECT * FROM slider WHERE ativo = 'S' ORDER BY id ASC";
		$exe_slider = mysql_query($sql_slider, $base) or die('Erro. Por favor tente mais tarde.');
		$num_slider = mysql_num_rows($exe_slider);
		if ($num_slider > 0){
			$ar_slider = array();
			while ($reg_slider = mysql_fetch_array($exe_slider, MYSQL_ASSOC)){
				$ar_slider[] = $reg_slider;
			}
		}
		?>
		
		<!-- slideshow -->
		<? if (isset($ar_slider)): ?>
        <div id="slide">
        	<a class="ant rpc" href="javascript: prev_cena()" title="Anterior">Anterior</a>
					<? $count_s = 1; ?>
					<? foreach($ar_slider as $slide): ?>
					<div class="cena<?=$count_s > 1 ? ' hide' : ''; ?>" id="cena<?=$count_s?>">
						<a href="<?=stripslashes($slide['link'])?>">
							<img src="/slider/<?=stripslashes($slide['img'])?>" class="fundo-cena" />
							<div class="cena-info">
								<h2 class="tit-cena-info"><?=stripslashes($slide['titulo'])?></h2>
								<p class="p-cena-info"><?=stripslashes($slide['subtitulo'])?></p>
								<p class="p-cena-info2">
									<?=stripslashes($slide['descricao'])?>
								</p>
								<a class="vermais" href="<?=stripslashes($slide['link'])?>" title="Ver mais">Mais detalhes</a>
							</div>
						</a>
						
					</div>
					<? $count_s++; ?>
					<? endforeach; ?>
					<!-- menu das cenas do slideshow -->
					<? if(count($ar_slider) > 1): ?>
					<div class="menu-cenas">
						<? $count_m = 1; ?>
						<? foreach($ar_slider as $slide): ?>
						<a class="a-0<?=$count_m?> rpc mn<?=$count_m == 1 ? ' at' : '';?>" href="javascript: trocar_banner_mn(<?=$count_m?>)" id="mn<?=$count_m?>">1</a>
						<? $count_m++; ?>
						<? endforeach; ?>
					</div>
					<script type="text/javascript">
						cena_inicial = 1;
						n_cenas = <?=count($ar_slider)?>;
						
						function next_cena(){
							clearInterval(slider);
							cena_inicial++;
							if (cena_inicial > n_cenas){
								cena_inicial = 1;
							}
							console.log("cena id: "+cena_inicial);
							trocar_banner(cena_inicial);
						}
						
						function trocar_banner_mn(cena_id){
							clearInterval(slider);
							trocar_banner(cena_id);
						}
						
						function prev_cena(){
							clearInterval(slider);
							cena_inicial--;
							if (cena_inicial < 1){
								cena_inicial = n_cenas;
							}
							console.log("cena id: "+cena_inicial);
							trocar_banner(cena_inicial);
						}
						
						function trocar_banner(id_cena){
							setmn(id_cena);
							$(".cena").fadeOut(1200);
							$("#cena"+id_cena).fadeIn(1200);
						}
						
						function setmn(mn_id){
							$(".mn").removeClass('at');
							$("#mn"+mn_id).addClass('at');
						}
						
						function next_cena_automatico(){
							cena_inicial++;
							if (cena_inicial > n_cenas){
								cena_inicial = 1;
							}
							console.log("cena id: "+cena_inicial);
							trocar_banner(cena_inicial);
						}
						
						function troca_cena_automaticamente(){
							next_cena_automatico();
						}
						
						var slider = setInterval(troca_cena_automaticamente, 6000);
					</script>
					<? endif; ?>
					<a class="pro rpc" href="javascript: next_cena()" title="Próxima">Próxima</a>
        </div>
		<? endif; ?>
		<!-- fim do slideshow -->
		<?php
		// pegando o ultimo imovel para aluguel
		$sql_aluguel = "SELECT * FROM imoveis WHERE finalidade = 1 AND ativo = 'S' ORDER BY RAND() LIMIT 1";
		$exe_aluguel = mysql_query($sql_aluguel, $base) or die("Erro. Por favor tente mais tarde.");
		$num_aluguel = mysql_num_rows($exe_aluguel);
		
		$sql_venda = "SELECT * FROM imoveis WHERE finalidade = 1 AND ativo = 'S' ORDER BY RAND() DESC LIMIT 1";
		$exe_venda = mysql_query($sql_venda, $base) or die("Erro. Por favor tente mais tarde.");
		$num_venda = mysql_num_rows($exe_venda);
		
		if ($num_aluguel > 0 && $num_venda > 0){
			$reg_aluguel = mysql_fetch_array($exe_aluguel, MYSQL_ASSOC);
			$reg_venda = mysql_fetch_array($exe_venda, MYSQL_ASSOC);
		?>
		<h2 class="h2master">Imóveis em destaque</h2>
		<div class="destaques">
			<div class="destaque" style="margin-right:94px;">
				<a href="/imovel/<?=sanitize_title(stripslashes($reg_aluguel['titulo']))?>-<?=$reg_aluguel['id_imovel']?>"><img class="img-destaque"  src="/imoveis/164x123/<?=$reg_aluguel['img1']?>" /></a>
				<div class="txt-destaque">
					<a class="cat-destaque" title="Aluguel" href="/aluguel">Aluguel</a>
					<a class="tit-destaque" title="<?=stripslashes($reg_aluguel['titulo'])?>" href="/imovel/<?=sanitize_title(stripslashes($reg_aluguel['titulo']))?>-<?=$reg_aluguel['id_imovel']?>"><?=limita_chars(stripslashes($reg_aluguel['titulo']),120)?></a>
					<p class="desc-destaque"><?=limita_chars(stripslashes($reg_aluguel['descricao']),75)?></p>
					<a class="vermais2" href="/imovel/<?=sanitize_title(stripslashes($reg_aluguel['titulo']))?>-<?=$reg_aluguel['id_imovel']?>" title="Mais detalhes do imóvel">Mais detalhes</a>
				</div>
			</div>
			<div class="destaque">
				<a href="/imovel/<?=sanitize_title(stripslashes($reg_venda['titulo']))?>-<?=$reg_venda['id_imovel']?>"><img class="img-destaque"  src="/imoveis/164x123/<?=$reg_venda['img1']?>" /></a>
				<div class="txt-destaque">
					<a class="cat-destaque" title="Aluguel" href="/aluguel">Aluguel</a>
					<a class="tit-destaque" title="<?=stripslashes($reg_venda['titulo'])?>" href="/imovel/<?=sanitize_title(stripslashes($reg_venda['titulo']))?>-<?=$reg_venda['id_imovel']?>"><?=limita_chars(stripslashes($reg_venda['titulo']),120)?></a>
					<p class="desc-destaque"><?=limita_chars(stripslashes($reg_venda['descricao']),75)?></p>
					<a class="vermais2" href="/imovel/<?=sanitize_title(stripslashes($reg_venda['titulo']))?>-<?=$reg_venda['id_imovel']?>" title="Mais detalhes do imóvel">Mais detalhes</a>
				</div>
			</div>
		</div>
		<?
		}
		?>
		<div class="chams">
			<div class="cham">
				<h2><a class="tit-cham" href="/empresa" title="Sobre a Empresa">Sobre a Empresa</a></h2>
				<p class="p-cham">
					A Hermes Empreendimentos e Administração Ltda. é uma empresa de engenharia e administração de bens.
				</p>
				<a class="btcham sobre rpc" href="/empresa">Continuar lendo</a>
			</div>
			<div class="cham">
				<h2><a class="tit-cham" href="/aluguel" title="Aluguel">Aluguel</a></h2>
				<p class="p-cham">
					A Hermes Empreendimentos possui diversas opções de <a href="/aluguel">aluguel de apartamentos e quitinetes a 50 metros da UFSC</a>, confira.
				</p>
				<a class="btcham aluguel rpc" href="/aluguel">Ver imóveis</a>
			</div>
			<div class="cham">
				<h2><a class="tit-cham" href="/venda" title="Vendas">Vendas</a></h2>
				<p class="p-cham">
					A Hermes possui imóveis selecionados para venda, <a title="Imóveis à venda" href="/venda">conheça os imóveis disponíveis</a> e faça um ótimo negócio.
				</p>
				<a class="btcham vendas rpc" href="/venda">Imóveis à venda</a>
			</div>
			<div class="cham" style="margin-right:0">
				<h2><a class="tit-cham" href="/contato" title="Fale conosco">Fale conosco</a></h2>
				<p class="p-cham">
					Entre em contato conosco, diretamente pelos nossos telefones ou por e-mail.
				</p>
				<a class="btcham fale rpc" href="/contato">Entre em contato</a>
			</div>
		</div>
    </div>
<? include "engine/html/rodape.php";?>
