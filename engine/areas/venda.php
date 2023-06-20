<? include "engine/html/topo.php";?>
	<div class="geral geral-int">
    <? include "engine/html/topo-site.php";?>
		<div class="listagem">
			<div class="head-lista">
				<h2 class="h2-lista">Imóveis para venda</h2>
				<select id="filtro" onchange="location.href=this.value">
					<option value="/aluguel">Todos</option>
          <?php
          $sql_tipos = "SELECT * FROM tipos WHERE status = 'S' ORDER BY tipo ASC";
          $exe_tipos = mysql_query($sql_tipos, $base) or die("Erro, por favor tente mais tarde.");
          $num_tipos = mysql_num_rows($exe_tipos);
          if ($num_tipos > 0){
            while ($reg_tipos = mysql_fetch_array($exe_tipos)){
          ?>
          <option value="/aluguel/?filtro=<?=$reg_tipos['id_tipo']?>"<?=(@$_GET['filtro'] == $reg_tipos['id_tipo']) ? ' selected="selected"' : '';?>><?=stripslashes($reg_tipos['tipo'])?></option>
          <?
            }
          }
          ?>
				</select>
			</div>
			<h3 class="tipo-imovel">
      <?
      if (isset($_GET['filtro'])){
        $id_tipo = normaltxt($_GET['filtro']);
        $sql_tipo = "SELECT tipo FROM tipos WHERE id_tipo = '$id_tipo' LIMIT 1";
        $exe_tipo = mysql_query($sql_tipo, $base) or die("Erro, por favor tente mais tarde.");
        $num_tipo = mysql_num_rows($exe_tipo);
        if ($num_tipo > 0){
          $reg_tipo = mysql_fetch_array($exe_tipo, MYSQL_ASSOC);
          echo $reg_tipo['tipo'];
        } else {
          echo "Todos os imóveis para alugar";
        }
      } else {
        echo "Todos os imóveis para alugar";
      }
      ?>
      </h3>
			<?
      $sql_aux = isset($_GET['filtro']) ? " AND id_tipo = '".normaltxt($_GET['filtro'])."'" : "";
      $sql_imovel = "SELECT * FROM imoveis WHERE ativo = 'S' AND finalidade = 2 $sql_aux ORDER BY titulo ASC";
      $exe_imovel = mysql_query($sql_imovel, $base) or die("Erro, por favor tente mais tarde.");
      $num_imovel = mysql_num_rows($exe_imovel);
      if ($num_imovel > 0){
        while ($reg_imovel = mysql_fetch_array($exe_imovel, MYSQL_ASSOC)){
      ?>
      <div class="imv-lista">	
				<div class="imv-infos">
					<a class="td-nome" title="<?=stripslashes($reg_imovel['titulo'])?>" href="/imovel/<?=sanitize_title(stripslashes($reg_imovel['titulo']))?>-<?=$reg_imovel['id_imovel']?>"><?=stripslashes($reg_imovel['titulo'])?></a>
					<div class="imv-fotos">
						<a href="/imovel/<?=sanitize_title(stripslashes($reg_imovel['titulo']))?>-<?=$reg_imovel['id_imovel']?>"><img src="/imoveis/133x100/<?=$reg_imovel['img1']?>" /></a>
            <? if(!empty($reg_imovel['img2'])): ?>
            <a href="/imovel/<?=sanitize_title(stripslashes($reg_imovel['titulo']))?>-<?=$reg_imovel['id_imovel']?>"><img src="/imoveis/133x100/<?=$reg_imovel['img2']?>" /></a>
            <? endif; ?>
					</div>
				</div>
				<div class="imv-infos2">
					<ul class="imv-ul">
						<li style="border-left:0 none;">
							<strong>Dormitórios</strong>
							<span><?=(!empty($reg_imovel['qtd_dormitorios'])) ? $reg_imovel['qtd_dormitorios'] : '-';?></span>
						</li>
						<li>
							<strong>Suítes</strong>
							<span><?=(!empty($reg_imovel['qtd_suites'])) ? $reg_imovel['qtd_suites'] : '-';?></span>
						</li>
						<li>
							<strong>Garagens</strong>
							<span>
								<?
								if (!empty($reg_imovel['qtd_vagas_garagem'])){
									switch($reg_imovel['qtd_vagas_garagem']){
										case 'S':
											echo "Sim";
											break;
										case 'N':
											echo "Não";
											break;
										case 'O':
											echo "Opcional";
											break;
										default:
											echo $reg_imovel['qtd_vagas_garagem'];
									}
								} else {
									echo "-";
								}
								?>
								</span>
						</li>
						<li>
							<strong>Área (m²)</strong>
							<span><?=(!empty($reg_imovel['area_util_m2'])) ? $reg_imovel['area_util_m2'] : '-';?></span>
						</li>
						<li>
							<strong>Valor R$</strong>
							<span><?=number_format($reg_imovel['valor_aluguel_venda'],2,",",".")?></span>
						</li>
					</ul>
					<p class="imv-desc">
          <?
          if (strlen($reg_imovel['descricao']) > 200){
            echo substr(stripslashes($reg_imovel['descricao']), 0, 200)."...";
          } else {
            echo stripslashes($reg_imovel['descricao']);
          }
          ?>
          </p>
					<a class="imv-mais" href="/imovel/<?=sanitize_title(stripslashes($reg_imovel['titulo']))?>-<?=$reg_imovel['id_imovel']?>" title="Mais detalhes deste imóvel">Mais detalhes »</a>
				</div>
			</div>
      <?
        }
      } else {
      ?>
      <p>Nenhum imóvel encontrado.</p>
      <?
      }
      ?>
		</div>

    </div>
<? include "engine/html/rodape.php";?>
