<?

if (!isset($_GET['id'])){
	header("Location: /");
	exit;
}

$id_imovel = normaltxt($_GET['id']);

$sql_imovel = "SELECT * FROM imoveis WHERE id_imovel = '$id_imovel' AND ativo = 'S' LIMIT 1";
$exe_imovel = mysql_query($sql_imovel, $base) or die('Erro. Por favor tente mais tarde.');
$num_imovel = mysql_num_rows($exe_imovel);
if ($num_imovel == 0){
	header("Location: /");
	exit;
}

$reg_imovel = mysql_fetch_array($exe_imovel, MYSQL_ASSOC);

$pageTitle = stripslashes($reg_imovel['titulo'])." &middot; Hermes Empreendimentos";

?>
<? include "engine/html/topo.php";?>
	<div class="geral geral-int">
    <? include "engine/html/topo-site.php";?>
		<div class="texto">
			<div class="esq-imovel">
				<a class="imovel-img-media fancy-img" rel="gallery1" id="link-img-imovel-gde" href="/imoveis/800x600/<?=$reg_imovel['img1']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img1']?>" id="img-imovel-gde" />
				</a>
				<? if(!empty($reg_imovel['img2'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img2']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img2']?>" />
				</a>
				<? endif; ?>
				<? if(!empty($reg_imovel['img3'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img3']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img3']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img4'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img4']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img4']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img5'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img5']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img5']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img6'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img6']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img6']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img7'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img7']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img7']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img8'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img8']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img8']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img9'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img9']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img9']?>" />
				</a>
				<? endif; ?>
				
				<? if(!empty($reg_imovel['img10'])): ?>
				<a class="imovel-img-media fancy-img hide" rel="gallery1" href="/imoveis/800x600/<?=$reg_imovel['img10']?>">
					<img src="/imoveis/400x300/<?=$reg_imovel['img10']?>" />
				</a>
				<? endif; ?>
				
				<ul class="imovel-imagens-peq" id="thumb-imovel">
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img1']?>', '/imoveis/400x300/<?=$reg_imovel['img1']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img1']?>" /></a>
					</li>
					<? if(!empty($reg_imovel['img2'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img2']?>', '/imoveis/400x300/<?=$reg_imovel['img2']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img2']?>" /></a>
					</li>
					<? endif; ?>
					<? if(!empty($reg_imovel['img3'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img3']?>', '/imoveis/400x300/<?=$reg_imovel['img3']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img3']?>" /></a>
					</li>
					<? endif; ?>
					<? if(!empty($reg_imovel['img4'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img4']?>', '/imoveis/400x300/<?=$reg_imovel['img4']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img4']?>" /></a>
					</li>
					<? endif; ?>
					<? if(!empty($reg_imovel['img5'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img5']?>', '/imoveis/400x300/<?=$reg_imovel['img5']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img5']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img5'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img5']?>', '/imoveis/400x300/<?=$reg_imovel['img5']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img5']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img6'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img6']?>', '/imoveis/400x300/<?=$reg_imovel['img6']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img6']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img7'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img7']?>', '/imoveis/400x300/<?=$reg_imovel['img7']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img7']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img8'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img8']?>', '/imoveis/400x300/<?=$reg_imovel['img8']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img8']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img9'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img9']?>', '/imoveis/400x300/<?=$reg_imovel['img9']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img9']?>" /></a>
					</li>
					<? endif; ?>
					
					<? if(!empty($reg_imovel['img10'])): ?>
					<li>
						<a href="javascript: troca_imagem('/imoveis/800x600/<?=$reg_imovel['img10']?>', '/imoveis/400x300/<?=$reg_imovel['img10']?>')"><img src="/imoveis/75x56/<?=$reg_imovel['img10']?>" /></a>
					</li>
					<? endif; ?>
				</ul>
				
				
				<h3 class="tipo-imovel">Localização</h3>
				<div class="imovel-mapa">
					<?=unhtmlentities(stripslashes($reg_imovel['google_maps']))?>
				</div>
			</div>
			<div class="dir-imovel">
				<h2 class="imovel-h2"><?=stripslashes($reg_imovel['titulo'])?></h2>
				<? if(!empty($reg_imovel['endereco1'])): ?>
				<p class="end-imovel">
					<?=stripslashes($reg_imovel['endereco1'])?>
				</p>
				<? endif; ?>
				<? if(!empty($reg_imovel['endereco2'])): ?>
				<p class="end-imovel2"><?=stripslashes($reg_imovel['endereco2'])?></p>
				<? endif; ?>
				<? if(!empty($reg_imovel['descricao'])): ?>
				<p class="desc-imovel">
					<?=stripslashes($reg_imovel['descricao'])?>
				</p>
				<? endif; ?>
				<ul class="icons-imovel">
					<li>
						<a href="/pdfs/contrato_modelo.pdf">
							<img src="/img/lay/contrato-ic.png" />
							<span>Contrato modelo</span>
						</a>
					</li>
					<li>
						<a href="/pdfs/cadastro-proponente.docx">
							<img src="/img/lay/cadastro-ic.png" />
							<span>Cadastro proponente</span>
						</a>
					</li>
					<li style="margin-right:0">
						<a href="/contato">
							<img src="/img/lay/contato-ic.png" />
							<span>Contato</span>
						</a>
					</li>
				</ul>
				<div class="imovel-infos">
					<h3 class="h3infos">Valores</h3>
					<? if(!empty($reg_imovel['valor_aluguel_venda'])): ?>
					<p class="p-info">
						<? if($reg_imovel['finalidade'] == 1):?>
						<strong>Valor do aluguel</strong>
						<? else: ?>
						<strong>Valor de venda</strong>
						<? endif;?>
						<span>R$ <?=number_format($reg_imovel['valor_aluguel_venda'],2,",",".")?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['valor_condominio'])): ?>
					<p class="p-info">
						<strong>Condomínio</strong>
						<span><?=stripslashes($reg_imovel['valor_condominio'])?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['valor_iptu'])): ?>
					<p class="p-info">
						<strong>IPTU</strong>
						<span><?=stripslashes($reg_imovel['valor_iptu'])?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['internet'])): ?>
					<p class="p-info">
						<strong>Internet</strong>
						<span><?=stripslashes($reg_imovel['internet'])?></span>
					</p>
					<? endif; ?>
				</div>
				<?
				if (
						$reg_imovel['ap_tipo_estudio'] == 'S' || 
						!empty($reg_imovel['qtd_dormitorios']) ||
						!empty($reg_imovel['qtd_dormitorios']) ||
						!empty($reg_imovel['qtd_suites']) ||
						!empty($reg_imovel['qtd_banheiros']) ||
						!empty($reg_imovel['mobilia']) ||
						!empty($reg_imovel['mobilia']) ||
						$reg_imovel['area_util_m2'] > 0 || 
						!empty($reg_imovel['qtd_vagas_garagem'])
				){
				?>
								<div class="imovel-infos">
					<h3 class="h3infos">Características do imóvel</h3>
					<? if($reg_imovel['ap_tipo_estudio'] == 'S'): ?>
					<p class="p-tipo">Apartamento tipo estúdio</p>
					<? endif; ?>
					<!--<p class="p-tipo">Especialmente desenhado para estudantes de graduação e pós-graduação que não pretendem adquirir mobília na sua estada em Florianópolis.</p>-->
					<? if(!empty($reg_imovel['qtd_dormitorios'])): ?>
					<p class="p-info">
						<strong>Dormitórios</strong>
						<span><?=$reg_imovel['qtd_dormitorios']?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['qtd_suites'])): ?>
					<p class="p-info">
						<strong>Suítes</strong>
						<span><?=stripslashes($reg_imovel['qtd_suites'])?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['qtd_banheiros'])): ?>
					<p class="p-info">
						<strong>Banheiros</strong>
						<span><?=$reg_imovel['qtd_banheiros']?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['mobilia'])): ?>
					<p class="p-info">
						<strong>Mobília</strong>
						<span>
						<?
						switch($reg_imovel['mobilia']){
							case 'S':
								echo 'Sim';
								break;
							case 'N':
								echo 'Não';
								break;
							case 'O':
								echo 'Opcional';
								break;
						}
						?>
						</span>
					</p>
					<? endif; ?>
					<? if($reg_imovel['area_util_m2'] > 0): ?>
					<p class="p-info">
						<strong>Área privativa - m²</strong>
						<span><?=$reg_imovel['area_util_m2']?></span>
					</p>
					<? endif; ?>
					<? if(!empty($reg_imovel['qtd_vagas_garagem'])): ?>
					<p class="p-info">
						<strong>Vagas de garagem</strong>
						<?
						if ($reg_imovel['qtd_vagas_garagem'] == 'S'){
							echo '<span>Sim</span>';
						} elseif ($reg_imovel['qtd_vagas_garagem'] == 'N'){
							echo '<span>Não</span>';
						} elseif ($reg_imovel['qtd_vagas_garagem'] == 'O') {
							echo '<span>Opcional</span>';
						} else {
							echo '<span>'.$reg_imovel['qtd_vagas_garagem'].'</span>';
						}
						?>
					</p>
					<? endif; ?>
				</div>
				<?	
				}
				?>
				
				<?
				if (
						!empty($reg_imovel['obs_condominio']) ||
						$reg_imovel['qtd_pavimentos'] > 0 ||
						$reg_imovel['qtd_elevadores'] > 0 ||
						$reg_imovel['qtd_blocos'] > 0 ||
						!empty($reg_imovel['hobby_box']) ||
						$reg_imovel['piscina'] == 'S' ||
						$reg_imovel['piscina'] == 'N' ||
						$reg_imovel['salao_festas'] == 'S' ||
						$reg_imovel['salao_festas'] == 'N' ||
						$reg_imovel['seguranca'] == 'S' ||
						$reg_imovel['seguranca'] == 'N'
				){
				?>
								<div class="imovel-infos">
					<h3 class="h3infos">Características do condomínio</h3>
					<? if(!empty($reg_imovel['obs_condominio'])): ?>
					<p class="p-tipo"><?=stripslashes($reg_imovel['obs_condominio'])?></p>
					<? endif; ?>
					<? if($reg_imovel['qtd_pavimentos'] > 0): ?>
					<p class="p-info">
						<strong>Número de pavimentos</strong>
						<span><?=$reg_imovel['qtd_pavimentos']?></span>
					</p>
					<? endif; ?>
					<? if($reg_imovel['qtd_elevadores'] > 0): ?>
					<p class="p-info">
						<strong>Número de elevadores</strong>
						<span><?=$reg_imovel['qtd_elevadores']?></span>
					</p>
					<? endif; ?>
					<? if($reg_imovel['qtd_blocos'] > 0): ?>
					<p class="p-info">
						<strong>Número de blocos</strong>
						<span><?=$reg_imovel['qtd_blocos']?></span>
					</p>
					<? endif; ?>
					<? if (!empty($reg_imovel['hobby_box'])): ?>
					
					
					
					<p class="p-info">
						<strong>Hobby box</strong>
						<?
						if ($reg_imovel['hobby_box'] == 'S'){
							echo '<span>Sim</span>';
						} elseif ($reg_imovel['hobby_box'] == 'N'){
							echo '<span>Não</span>';
						} elseif ($reg_imovel['hobby_box'] == 'O') {
							echo '<span>Opcional</span>';
						}
						?>
					</p>
					<? endif; ?>
					
					<? if($reg_imovel['piscina'] == 'S'): ?>
					<p class="p-info">
						<strong>Piscina</strong>
						<span>Sim</span>
					</p>
					<? elseif($reg_imovel['piscina'] == 'N'): ?>
					<p class="p-info">
						<strong>Piscina</strong>
						<span>Não</span>
					</p>
					<? endif; ?>
					
					<? if($reg_imovel['salao_festas'] == 'S'): ?>
					<p class="p-info">
						<strong>Salão de festas</strong>
						<span>Sim</span>
					</p>
					<? elseif($reg_imovel['salao_festas'] == 'N'): ?>
					<p class="p-info">
						<strong>Salão de festas</strong>
						<span>Não</span>
					</p>
					<? endif; ?>
					
					<? if($reg_imovel['seguranca'] == 'S'): ?>
					<p class="p-info">
						<strong>Segurança</strong>
						<span>Sim</span>
					</p>
					<? elseif($reg_imovel['seguranca'] == 'N'): ?>
					<p class="p-info">
						<strong>Segurança</strong>
						<span>Não</span>
					</p>
					<? endif; ?>
				</div>
				<?
				}
				?>
				<? if(!empty($reg_imovel['obs'])): ?>
				<div class="imovel-infos">
					<h3 class="h3infos">Observações</h3>
					<p class="p-tipo"><?=stripslashes($reg_imovel['obs'])?></p>
				</div>
				<? endif; ?>
				
			</div>
			
			
		</div>
    </div>
	<script type="text/javascript">
		function troca_imagem(link, img){
			$("#link-img-imovel-gde").attr('href', link);
			$("#img-imovel-gde").attr('src', img);
		}
		
		$(".fancy-img").fancybox();
	</script>
<? include "engine/html/rodape.php";?>
