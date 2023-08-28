<?php	
	// Verificando se é include
	$arq_bloco = 'imoveis.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){	
		exit;
  }
	
	require "libs/imgresize/hft_image.php";
	
	// configuracoes de tamanhos de imagens
	$max_w = 800;
	$max_h = 600;
	
	$ar_tamanhos = array(
		'75x56', '133x100', '164x123', '400x300', '800x600'
	);
  
	$coluna_busca = array(
				'id_imovel'=>'Id',
				'titulo' => 'Título'
				
				);
	$tipo_busca = array(
				1=>'igual a',
				2=>'diferente de',
				3=>'inicia com',
				4=>'contém',
				5=>'termina com',
				6=>'maior que',
				7=>'menor que'
				);
	
	// Definindo o número de registros por página					
	$num_por_pagina = 20;
	// Caso uma página ainda não estiver sido definida coloca valor 1
	$pag_atual = (isset($_POST['grid_pg'])) ? $_POST['grid_pg'] : 1;
	// achando o primeiro registro para a paginação
	$primeiro_registro = ($pag_atual * $num_por_pagina) - $num_por_pagina; 
	
	if (isset($_GET['acao']) && $_GET['acao'] == 'list'){
	
		// SQL que faz a contagem total do número de registros
		if (!isset($_POST['grid'])){
			$sql_total = "SELECT count(*) as total_pag FROM imoveis";
			$sql_lista = "SELECT * FROM imoveis ORDER BY id_imovel LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'id_imovel';
				$txt_grid_tipo  = 1;
				$txt_grid_txt   = '';
			} else {
				$txt_grid_campo = normaltxt($_POST['grid_campo']);
				$txt_grid_tipo  = normaltxt($_POST['grid_tipo']);
				$txt_grid_txt   = normaltxt($_POST['grid_txt']);
			}
			
			// busca feita pelo usuário
			switch($_POST['grid_tipo']){
				case 1:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM imoveis WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM imoveis WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
			}
			
		}
		
		// executando as duas querys
		$exe_total = mysql_query($sql_total, $base) or aw_error(mysql_error());
		$exe_lista = mysql_query($sql_lista, $base) or aw_error(mysql_error());
		
		// total de páginas
		$reg_total = mysql_fetch_array($exe_total, MYSQL_ASSOC);
		$total_lista = $reg_total['total_pag'];
		
		// total de registros da busca ou listagem
		$total_registros = mysql_num_rows($exe_lista);
		
		if ($total_registros <= 0){
          $btn_crud = 'disabled="disabled"';
        } else {
          $btn_crud = '';
        }
		
		$total_paginas = ceil($total_lista / $num_por_pagina);
		
		$prev = $pag_atual - 1;
		$next = $pag_atual + 1;
		
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc ?>&acao=list" method="post" class="form_grid">
		<input type="hidden" name="grid" value="1" />
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
  			<tr class="grid_topo">
   			 <td width="70%">  
       		 <select name="grid_campo" class="campo_grid">
			 <?php
			 form_coluna_busca($coluna_busca);
			 ?>
        	 </select>
        	 <select name="grid_tipo" class="campo_grid">
        	 <?php
			 form_tipo_busca($tipo_busca);
			 ?>
        	 </select>
        	 <input type="text" name="grid_txt" class="campo_grid" value="<?php echo @$_POST['grid_txt'] ?>" />
			 <input type="submit" value="Buscar" class="campo_grid" />
			 </form>
      		 </td>
    		 <td width="30%" align="right">
			 <?php
			// se número total de páginas for maior que a página corrente, então temos link para a próxima página
			if ($total_paginas > $pag_atual) {
				$next_link = "<input type=\"button\" value=\" >> \" onClick=\"prev_pg('".$blc."',".$next.",'paginacao')\" class=\"campo_grid\">";
			} else { // senão não há link para a próxima página
				$next_link = "<input type=\"button\" value=\" >> \" class=\"campo_grid\" disabled>";
			}
			 // se página maior que 1 (um), então temos link para a página anterior
			 if ($pag_atual > 1) {
		 		$prev_link = "<input type=\"button\" value=\" << \" onClick=\"prev_pg('".$blc."',".$prev.",'paginacao')\" class=\"campo_grid\">";
	 		 } else { // senão não há link para a página anterior
				$prev_link = "<input type=\"button\" value=\" << \" class=\"campo_grid\" disabled>";
			 }
			 ?>
			 <!-- botão de voltar -->
			 <form action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc ?>&acao=list" method="post" class="form_grid" name="paginacao" id="paginacao">
			 <?php			 
			 if (isset($_POST['grid'])){
			 ?>
			 <input type="hidden" name="grid_pg" value="1" />
			 <input type="hidden" name="grid_campo" value="<?php echo $_POST['grid_campo']?>" />
			 <input type="hidden" name="grid_tipo" value="<?php echo $_POST['grid_tipo']?>" />
			 <input type="hidden" name="grid_txt" value="<?php echo $_POST['grid_txt']?>" />
			 <input type="hidden" name="grid" value="1" />
			 <?php
			 } else {
			 ?>
			 <input type="hidden" name="grid_pg" value="1" />
			 <?php
			 }
			 echo $prev_link;
			 echo $next_link;
			 ?>
			
			 </form>
			 
			 </td>
  		</tr>
		</table>
		<?
        if (isset($_GET['sucesso'])):
        ?>
        <ul id="sucesso_sistema">
          <li>Imóvel excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
        <td width="4%" class="top_mod_blc">&nbsp;</td>
    		<td width="8%" class="top_mod_blc">&nbsp;ID</td>
				<td width="78%" class="top_mod_blc">&nbsp;TÍTULO</td>
				<td width="10%" class="top_mod_blc">&nbsp;ATIVO</td>
  		</tr>
		<?php
		$linha_bg = 0;
		while ($reg_lista = mysql_fetch_array($exe_lista, MYSQL_ASSOC)){
			if ($linha_bg == 0){
				$checked = 'checked="checked"';
			} else {
				$checked = '';
			}
		?>
  		<tr>
        <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_imovel'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
    		<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['id_imovel'])?></td>
				<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['titulo'])?></td>
				<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?=($reg_lista['ativo'] == 'S') ? 'Sim' : 'Não'; ?></td>
  		</tr>
		<?php
			$linha_bg++;
		}
		?>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
  		<tr class="grid_topo">
    		<td class="grid_rodape">&nbsp;Total de <?php echo $total_lista ?> registro(s)<br />
      			&nbsp;P&aacute;gina <?php echo $pag_atual ?> de <?php echo $total_paginas ?> </td>
   		  <td align="right">
			<input name="button" type="button" class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>?blc=<?php echo $blc ?>&acao=Incluir'" value="Cadastrar" />
			<input type="button" value="Alterar" class="campo_grid" onclick="gen_submit('<?php echo $blc?>','alt','grid_grid')" <?=$btn_crud?> />
			<input type="button" value="Excluir" class="campo_grid" onclick="gen_submit('<?php echo $blc?>','del','grid_grid')" <?=$btn_crud?> /></td>
  		</tr>
		</table>
		</form>
		<?php
		
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Incluir')){
		// validando informações e cadastrando caso esteja correto.
		if (isset($_POST['form_submit'])){
			// validação começa aqui
			// validando finalidade
      if (isset($_POST['finalidade'])){
        if (empty($_POST['finalidade'])){
          $erro[] = 'Informe a finalidade.';
        } elseif (!in_array($_POST['finalidade'], array(1,2))){
          $erro[] = 'Informe a finalidade.';
        }
      } else {
        $erro[] = 'Informe a finalidade.';
      }
      // validando id_tipo
      if (isset($_POST['id_tipo'])){
        if (empty($_POST['id_tipo'])){
          $erro[] = 'Informe o tipo de imóvel.';
        } else {
          $id_tipo = normaltxt($_POST['id_tipo']);
          $sql_tem_tipo = "SELECT COUNT(1) AS tem_tipo FROM tipos WHERE id_tipo = '$id_tipo'";
          $exe_tem_tipo = mysql_query($sql_tem_tipo, $base) or aw_error(mysql_error());
          $reg_tem_tipo = mysql_fetch_array($exe_tem_tipo, MYSQL_ASSOC);
          if ($reg_tem_tipo['tem_tipo'] == 0){
            $erro[] = 'O tipo de imóvel escolhido não existe.';
          }
        }
      } else {
        $erro[] = 'Informe o tipo de imóvel.';
      }
      // validando titulo
      if (isset($_POST['titulo'])){
        if (empty($_POST['titulo'])){
          $erro[] = 'Informe o título.';
        } elseif (strlen($_POST['titulo']) > 100){
          $erro[] = 'O título deve conter no máximo 100 caracteres.';
        }
      } else {
        $erro[] = 'Informe o título.';
      }
			// validando endereco1
			if (isset($_POST['endereco1'])){
				if (!empty($_POST['endereco1']) && strlen($_POST['endereco1']) > 250){
					$erro[] = 'O endereço 1 deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o endereço 1.';
			}
			// validando endereco2
			if (isset($_POST['endereco2'])){
				if (!empty($_POST['endereco2']) && strlen($_POST['endereco2']) > 250){
					$erro[] = 'O endereço 2 deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o endereço 2.';
			}
      // validando descricao
      if (isset($_POST['descricao'])){
        if (empty($_POST['descricao'])){
          $erro[] = 'Informe a descrição.';
        } elseif (strlen($_POST['descricao']) > 500){
          $erro[] = 'A descrição deve conter no máximo 500 caracteres.';
        }
      } else {
        $erro[] = 'Informe a descrição.';
      }
      // validando google_maps
      if (isset($_POST['google_maps'])){
        if (empty($_POST['google_maps'])){
          $erro[] = 'Informe o código do Google Maps.';
        } elseif (strlen($_POST['google_maps']) > 5000){
          $erro[] = 'O código do Google Maps está muito grande.';
        }
      } else {
        $erro[] = 'Informe o código do Google Maps.';
      }
      // validando valor_aluguel_venda
      if (isset($_POST['valor_aluguel_venda'])){
        if (empty($_POST['valor_aluguel_venda'])){
          $erro[] = 'Informe o valor de aluguel / venda.';
        } elseif (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['valor_aluguel_venda'])) {
          $erro[] = 'O valor de aluguel / venda está incorreto.';
        }
      } else {
        $erro[] = 'Informe o valor de aluguel / venda.';
      }
			// validando internet
			if (isset($_POST['internet'])){
				
			} else {
				$erro[] = 'Informe sobre a internet.';
			}
			// validando ap_tipo_estudio
			if (isset($_POST['ap_tipo_estudio'])){
				if (!empty($_POST['ap_tipo_estudio'])){
					if (!in_array($_POST['ap_tipo_estudio'], array('','S','N'))) {
						$erro[] = 'Informe se o apartamento é do tipo estúdio.';
					}
				}
			} else {
				$erro[] = 'Informe se o apartamento é do tipo estúdio.';
			}
			// validando qtd_dormitorios
			if (isset($_POST['qtd_dormitorios'])){
				if (!empty($_POST['qtd_dormitorios']) && strlen($_POST['qtd_dormitorios']) > 100){
					$erro[] = 'A quantidade de dormitorios deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de domitórios.';
			}
			// validando qtd_suites
			if (isset($_POST['qtd_suites'])){
				if (!empty($_POST['qtd_suites']) && strlen($_POST['qtd_suites']) > 100){
					$erro[] = 'A quantidade de suítes deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de suítes.';
			}
			// validando qtd_banheiros
			if (isset($_POST['qtd_banheiros'])){
				if (!empty($_POST['qtd_banheiros']) && strlen($_POST['qtd_banheiros']) > 100){
					$erro[] = 'A quantidade de banheiros deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de banheiros.';
			}
			// validando qtd_vagas_garagem
      if (isset($_POST['qtd_vagas_garagem'])){
				if (!empty($_POST['qtd_vagas_garagem'])){
					if (!in_array($_POST['qtd_vagas_garagem'], array('S','N','O', 1, 2, 3))){
						$erro[] = 'A quantidade sobre a vaga na garagem.';
					}
				}
			} else {
				$erro[] = 'A quantidade sobre a vaga na garagem.';
			}
			// validando area_util_m2
			if (isset($_POST['area_util_m2'])){
				if (!empty($_POST['area_util_m2'])){
					if (!is_numeric($_POST['area_util_m2'])){
						$erro[] = 'A área útil deve ser numérica.';
					}
				}
			} else {
				$erro[] = 'Informe a área útil em m<sup>2</sup>.';
			}
			// validando eletrodomesticos
			if (isset($_POST['eletrodomesticos'])){
				if (!empty($_POST['eletrodomesticos']) && strlen($_POST['eletrodomesticos']) > 2000){
					$erro[] = 'Descrição dos eletrodomésticos com no máximo 2000 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição dos eletrodomésticos.';
			}
			// validando mobilia
			if (isset($_POST['mobilia'])){
				if (!empty($_POST['mobilia']) && strlen($_POST['mobilia']) > 2000){
					$erro[] = 'Descrição das mobílias com no máximo 2000 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição das mobílias.';
			}
			// validar valor_iptu
			if (isset($_POST['valor_iptu'])){
        
      } else {
        $erro[] = 'Informe o valor do IPTU.';
      }
			// validar valor_condominio
			if (isset($_POST['valor_condominio'])){
        if (!empty($_POST['valor_condominio']) && strlen($_POST['valor_condominio']) > 50){
          $erro[] = 'Valor do condomínio com no máximo 50 caracteres.';
				}
      } else {
        $erro[] = 'Informe o valor do condomínio.';
      }
			// validando obs_condominio
			if (isset($_POST['obs_condominio'])){
				
			} else {
				$erro[] = 'Informe as observações do condomínio.';
			}
			// validando nome_condominio_predio
			if (isset($_POST['nome_condominio_predio'])){
				if (!empty($_POST['nome_condominio_predio']) && strlen($_POST['nome_condominio_predio']) > 200){
					$erro[] = 'Nome do condomínio / prédio com no máximo 200 caracteres.';
				}
			} else {
				$erro[] = 'Informe o nome do condomínio / prédio.';
			}
			// validando qtd_pavimentos
			if (isset($_POST['qtd_pavimentos'])){
        if (!empty($_POST['qtd_pavimentos'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_pavimentos'])) {
					  $erro[] = 'A quantidade de pavimentos está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de pavimentos.';
      }
			// validando qtd_elevadores
			if (isset($_POST['qtd_elevadores'])){
        if (!empty($_POST['qtd_elevadores'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_elevadores'])) {
					  $erro[] = 'A quantidade de elevadores está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de elevadores.';
      }
			// validando qtd_blocos
			if (isset($_POST['qtd_blocos'])){
        if (!empty($_POST['qtd_blocos'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_blocos'])) {
					  $erro[] = 'A quantidade de blocos está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de blocos.';
      }
			// validando salao_festas
			if (isset($_POST['salao_festas'])){
				if (!empty($_POST['salao_festas'])){
					if (!in_array($_POST['salao_festas'], array('S', 'N'))){
						$erro[] = 'Informe se possui salão de festas.';
					}
				}
			} else {
				$erro[] = 'Informe se possui salão de festas.';
			}
			// validando piscina
			if (isset($_POST['piscina'])){
				if (!empty($_POST['piscina'])){
					if (!in_array($_POST['piscina'], array('S', 'N'))){
						$erro[] = 'Informe se possui piscina.';
					}
				}
			} else {
				$erro[] = 'Informe se possui piscina.';
			}
			// validando seguranca
			if (isset($_POST['seguranca'])){
				if (!empty($_POST['seguranca'])){
					if (!in_array($_POST['seguranca'], array('S', 'N', 'H'))){
						$erro[] = 'Informe se possui seguranças.';
					}
				}
			} else {
				$erro[] = 'Informe se possui seguranças.';
			}
			// validando hobby_box
			if (isset($_POST['hobby_box'])){
				if (!empty($_POST['hobby_box'])){
					if (!in_array($_POST['hobby_box'], array('S', 'N', 'O'))){
						$erro[] = 'Informe se possui hobby box.';
					}
				}
			} else {
				$erro[] = 'Informe se possui hobby box.';
			}
			// img1
			if (isset($_FILES['img1']['name'], $_FILES['img1']['size'], $_FILES['img1']['tmp_name'], $_FILES['img1']['type'])){
				if (empty($_FILES['img1']['name'])){
          $erro[] = "Informe a imagem 1.";
        } else {
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img1']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 1 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img1']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 1 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img1']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img1']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img1']['type']),-3) != 'gif' && substr(strtolower($_FILES['img1']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 1 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img1']['tmp_name']) || is_dir($_FILES['img1']['name']) || getimagesize($_FILES['img1']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 1 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img1']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 1 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 1.";
			}
			// img2
			if (isset($_FILES['img2']['name'], $_FILES['img2']['size'], $_FILES['img2']['tmp_name'], $_FILES['img2']['type'])){
				if (!empty($_FILES['img2']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img2']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 2 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img2']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 2 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img2']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img2']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img2']['type']),-3) != 'gif' && substr(strtolower($_FILES['img2']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 2 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img2']['tmp_name']) || is_dir($_FILES['img2']['name']) || getimagesize($_FILES['img2']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 2 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img2']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 2 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 2.";
			}
			// img3
			if (isset($_FILES['img3']['name'], $_FILES['img3']['size'], $_FILES['img3']['tmp_name'], $_FILES['img3']['type'])){
				if (!empty($_FILES['img3']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img3']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 3 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img3']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 3 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img3']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img3']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img3']['type']),-3) != 'gif' && substr(strtolower($_FILES['img3']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 3 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img3']['tmp_name']) || is_dir($_FILES['img3']['name']) || getimagesize($_FILES['img3']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 3 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img3']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 3 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 3.";
			}
			// img4
			if (isset($_FILES['img4']['name'], $_FILES['img4']['size'], $_FILES['img4']['tmp_name'], $_FILES['img4']['type'])){
				if (!empty($_FILES['img4']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img4']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 4 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img4']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 4 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img4']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img4']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img4']['type']),-3) != 'gif' && substr(strtolower($_FILES['img4']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 4 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img4']['tmp_name']) || is_dir($_FILES['img4']['name']) || getimagesize($_FILES['img4']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 4 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img4']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 4 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 4.";
			}
			// img5
			if (isset($_FILES['img5']['name'], $_FILES['img5']['size'], $_FILES['img5']['tmp_name'], $_FILES['img5']['type'])){
				if (!empty($_FILES['img5']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img5']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 5 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img5']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 5 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img5']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img5']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img5']['type']),-3) != 'gif' && substr(strtolower($_FILES['img5']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 5 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img5']['tmp_name']) || is_dir($_FILES['img5']['name']) || getimagesize($_FILES['img5']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 5 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img5']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 5 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 5.";
			}
			// img6
			if (isset($_FILES['img6']['name'], $_FILES['img6']['size'], $_FILES['img6']['tmp_name'], $_FILES['img6']['type'])){
				if (!empty($_FILES['img6']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img6']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 6 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img6']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 6 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img6']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img6']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img6']['type']),-3) != 'gif' && substr(strtolower($_FILES['img6']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 6 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img6']['tmp_name']) || is_dir($_FILES['img6']['name']) || getimagesize($_FILES['img6']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 6 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img6']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 6 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 6.";
			}
			// img7
			if (isset($_FILES['img7']['name'], $_FILES['img7']['size'], $_FILES['img7']['tmp_name'], $_FILES['img7']['type'])){
				if (!empty($_FILES['img7']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img7']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 7 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img7']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 7 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img7']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img7']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img7']['type']),-3) != 'gif' && substr(strtolower($_FILES['img7']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 7 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img7']['tmp_name']) || is_dir($_FILES['img7']['name']) || getimagesize($_FILES['img7']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 7 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img7']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 7 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 7.";
			}
			// img8
			if (isset($_FILES['img8']['name'], $_FILES['img8']['size'], $_FILES['img8']['tmp_name'], $_FILES['img8']['type'])){
				if (!empty($_FILES['img8']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img8']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 8 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img8']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 8 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img8']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img8']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img8']['type']),-3) != 'gif' && substr(strtolower($_FILES['img8']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 8 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img8']['tmp_name']) || is_dir($_FILES['img8']['name']) || getimagesize($_FILES['img8']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 8 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img8']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 8 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 8.";
			}
			// img9
			if (isset($_FILES['img9']['name'], $_FILES['img9']['size'], $_FILES['img9']['tmp_name'], $_FILES['img9']['type'])){
				if (!empty($_FILES['img9']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img9']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 9 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img9']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 9 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img9']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img9']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img9']['type']),-3) != 'gif' && substr(strtolower($_FILES['img9']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 9 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img9']['tmp_name']) || is_dir($_FILES['img9']['name']) || getimagesize($_FILES['img9']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 9 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img9']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 9 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 9.";
			}
			// img10
			if (isset($_FILES['img10']['name'], $_FILES['img10']['size'], $_FILES['img10']['tmp_name'], $_FILES['img10']['type'])){
				if (!empty($_FILES['img10']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img10']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 10 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img10']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 10 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img10']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img10']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img10']['type']),-3) != 'gif' && substr(strtolower($_FILES['img10']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 10 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img10']['tmp_name']) || is_dir($_FILES['img10']['name']) || getimagesize($_FILES['img10']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 10 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img10']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 10 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 10.";
			}
			// validando obs
			if (isset($_POST['obs'])){
				
			} else {
				$erro[] = 'Informe as observações.';
			}
			// validando ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo']) || !in_array($_POST['ativo'], array('S', 'N'))){
					$erro[] = 'Informe se está ou não está ativo.';
				}
			} else {
				$erro[] = 'Informe se está ou não está ativo.';
			}
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$finalidade							= normaltxt($_POST['finalidade']);
				$id_tipo								= normaltxt($_POST['id_tipo']);
				$titulo									= normaltxt($_POST['titulo']);
				$descricao							= normaltxt($_POST['descricao']);
				$google_maps						= normaltxt($_POST['google_maps']);
				$valor_aluguel_venda		= formata_dinheiro($_POST['valor_aluguel_venda']);
				$ap_tipo_estudio 				= normaltxt($_POST['ap_tipo_estudio']);
				$qtd_dormitorios				= normaltxt($_POST['qtd_dormitorios']);
				$qtd_suites							= normaltxt($_POST['qtd_suites']);
				$qtd_banheiros					= normaltxt($_POST['qtd_banheiros']);
				$qtd_vagas_garagem			= normaltxt($_POST['qtd_vagas_garagem']);
				$area_util_m2						= normaltxt($_POST['area_util_m2']);
				$eletrodomesticos				= normaltxt($_POST['eletrodomesticos']);
				$mobilia								= normaltxt($_POST['mobilia']);
				$valor_iptu							= normaltxt($_POST['valor_iptu']);
				$valor_condominio				= normaltxt($_POST['valor_condominio']);
				$nome_condominio_predio	= normaltxt($_POST['nome_condominio_predio']);
				$qtd_pavimentos					= normaltxt($_POST['qtd_pavimentos']);	
				$qtd_elevadores 				= normaltxt($_POST['qtd_elevadores']);
				$qtd_blocos 						= normaltxt($_POST['qtd_blocos']);
				$salao_festas						= normaltxt($_POST['salao_festas']);
				$piscina 								= normaltxt($_POST['piscina']);
				$seguranca 							= normaltxt($_POST['seguranca']);
				$hobby_box							= normaltxt($_POST['hobby_box']);
				$ativo									= normaltxt($_POST['ativo']);
				$obs									  = normaltxt($_POST['obs']);
				$internet							  = normaltxt($_POST['internet']);
				$obs_condominio					= normaltxt($_POST['obs_condominio']);
				$endereco1							= normaltxt($_POST['endereco1']);
				$endereco2							= normaltxt($_POST['endereco2']);
				
				// img1
				$id_img1 = gen_numero('imoveis');
				if (substr($_FILES['img1']['name'],-3) == 'jpg' || substr($_FILES['img1']['name'],-3) == 'JPG'){
					$ext1 = ".jpg";
				} elseif (substr($_FILES['img1']['name'],-3) == 'gif' || substr($_FILES['img1']['name'],-3) == 'GIF'){
					$ext1 = ".gif";
				} else {
					$ext1 = ".jpg";
				}
				$img1 = $id_img1.$ext1;
				// 800x600
				$img = new hft_image($_FILES['img1']['tmp_name']);
				$img->resize(800,600,"-");
				$img->output_resized("../imoveis/800x600/".$img1);
				// 400x300
				$img = new hft_image($_FILES['img1']['tmp_name']);
				$img->resize(400,300,"-");
				$img->output_resized("../imoveis/400x300/".$img1);
				// 164x123
				$img = new hft_image($_FILES['img1']['tmp_name']);
				$img->resize(164, 123,"-");
				$img->output_resized("../imoveis/164x123/".$img1);
				// 133x100
				$img = new hft_image($_FILES['img1']['tmp_name']);
				$img->resize(133, 100,"-");
				$img->output_resized("../imoveis/133x100/".$img1);
				// 75x56
				$img = new hft_image($_FILES['img1']['tmp_name']);
				$img->resize(75, 56,"-");
				$img->output_resized("../imoveis/75x56/".$img1);
				
				// img2
				if (!empty($_FILES['img2']['name'])){
					$id_img2 = gen_numero('imoveis');
					if (substr($_FILES['img2']['name'],-3) == 'jpg' || substr($_FILES['img2']['name'],-3) == 'JPG'){
						$ext2 = ".jpg";
					} elseif (substr($_FILES['img2']['name'],-3) == 'gif' || substr($_FILES['img2']['name'],-3) == 'GIF'){
						$ext2 = ".gif";
					} else {
						$ext2 = ".jpg";
					}
					$img2 = $id_img2.$ext2;
					// 800x600
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img2);
					// 400x300
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img2);
					// 164x123
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img2);
					// 133x100
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img2);
					// 75x56
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img2);
				} else {
					$img2 = '';
				}
				
				// img3
				if (!empty($_FILES['img3']['name'])){
					$id_img3 = gen_numero('imoveis');
					if (substr($_FILES['img3']['name'],-3) == 'jpg' || substr($_FILES['img3']['name'],-3) == 'JPG'){
						$ext3 = ".jpg";
					} elseif (substr($_FILES['img3']['name'],-3) == 'gif' || substr($_FILES['img3']['name'],-3) == 'GIF'){
						$ext3 = ".gif";
					} else {
						$ext3 = ".jpg";
					}
					$img3 = $id_img3.$ext3;
					// 800x600
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img3);
					// 400x300
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img3);
					// 164x123
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img3);
					// 133x100
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img3);
					// 75x56
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img3);
				} else {
					$img3 = '';
				}
				
				// img4
				if (!empty($_FILES['img4']['name'])){
					$id_img4 = gen_numero('imoveis');
					if (substr($_FILES['img4']['name'],-3) == 'jpg' || substr($_FILES['img4']['name'],-3) == 'JPG'){
						$ext4 = ".jpg";
					} elseif (substr($_FILES['img4']['name'],-3) == 'gif' || substr($_FILES['img4']['name'],-3) == 'GIF'){
						$ext4 = ".gif";
					} else {
						$ext4 = ".jpg";
					}
					$img4 = $id_img4.$ext4;
					// 800x600
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img4);
					// 400x300
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img4);
					// 164x123
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img4);
					// 133x100
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img4);
					// 75x56
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img4);
				} else {
					$img4 = '';
				}
				
				// img5
				if (!empty($_FILES['img5']['name'])){
					$id_img5 = gen_numero('imoveis');
					if (substr($_FILES['img5']['name'],-3) == 'jpg' || substr($_FILES['img5']['name'],-3) == 'JPG'){
						$ext5 = ".jpg";
					} elseif (substr($_FILES['img5']['name'],-3) == 'gif' || substr($_FILES['img5']['name'],-3) == 'GIF'){
						$ext5 = ".gif";
					} else {
						$ext5 = ".jpg";
					}
					$img5 = $id_img5.$ext5;
					// 800x600
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img5);
					// 400x300
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img5);
					// 164x123
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img5);
					// 133x100
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img5);
					// 75x56
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img5);
				} else {
					$img5 = '';
				}
				
				// img6
				if (!empty($_FILES['img6']['name'])){
					$id_img6 = gen_numero('imoveis');
					if (substr($_FILES['img6']['name'],-3) == 'jpg' || substr($_FILES['img6']['name'],-3) == 'JPG'){
						$ext6 = ".jpg";
					} elseif (substr($_FILES['img6']['name'],-3) == 'gif' || substr($_FILES['img6']['name'],-3) == 'GIF'){
						$ext6 = ".gif";
					} else {
						$ext6 = ".jpg";
					}
					$img6 = $id_img6.$ext6;
					// 800x600
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img6);
					// 400x300
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img6);
					// 164x123
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img6);
					// 133x100
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img6);
					// 75x56
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img6);
				} else {
					$img6 = '';
				}
				
				// img7
				if (!empty($_FILES['img7']['name'])){
					$id_img7 = gen_numero('imoveis');
					if (substr($_FILES['img7']['name'],-3) == 'jpg' || substr($_FILES['img7']['name'],-3) == 'JPG'){
						$ext7 = ".jpg";
					} elseif (substr($_FILES['img7']['name'],-3) == 'gif' || substr($_FILES['img7']['name'],-3) == 'GIF'){
						$ext7 = ".gif";
					} else {
						$ext7 = ".jpg";
					}
					$img7 = $id_img7.$ext7;
					// 800x600
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img7);
					// 400x300
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img7);
					// 164x123
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img7);
					// 133x100
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img7);
					// 75x56
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img7);
				} else {
					$img7 = '';
				}
				
				// img8
				if (!empty($_FILES['img8']['name'])){
					$id_img8 = gen_numero('imoveis');
					if (substr($_FILES['img8']['name'],-3) == 'jpg' || substr($_FILES['img8']['name'],-3) == 'JPG'){
						$ext8 = ".jpg";
					} elseif (substr($_FILES['img8']['name'],-3) == 'gif' || substr($_FILES['img8']['name'],-3) == 'GIF'){
						$ext8 = ".gif";
					} else {
						$ext8 = ".jpg";
					}
					$img8 = $id_img8.$ext8;
					// 800x600
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img8);
					// 400x300
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img8);
					// 164x123
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img8);
					// 133x100
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img8);
					// 75x56
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img8);
				} else {
					$img8 = '';
				}
				
				// img9
				if (!empty($_FILES['img9']['name'])){
					$id_img9 = gen_numero('imoveis');
					if (substr($_FILES['img9']['name'],-3) == 'jpg' || substr($_FILES['img9']['name'],-3) == 'JPG'){
						$ext9 = ".jpg";
					} elseif (substr($_FILES['img9']['name'],-3) == 'gif' || substr($_FILES['img9']['name'],-3) == 'GIF'){
						$ext9 = ".gif";
					} else {
						$ext9 = ".jpg";
					}
					$img9 = $id_img9.$ext9;
					// 800x600
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img9);
					// 400x300
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img9);
					// 164x123
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img9);
					// 133x100
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img9);
					// 75x56
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img9);
				} else {
					$img9 = '';
				}
				
				// img10
				if (!empty($_FILES['img10']['name'])){
					$id_img10 = gen_numero('imoveis');
					if (substr($_FILES['img10']['name'],-3) == 'jpg' || substr($_FILES['img10']['name'],-3) == 'JPG'){
						$ext10 = ".jpg";
					} elseif (substr($_FILES['img10']['name'],-3) == 'gif' || substr($_FILES['img10']['name'],-3) == 'GIF'){
						$ext10 = ".gif";
					} else {
						$ext10 = ".jpg";
					}
					$img10 = $id_img10.$ext10;
					// 800x600
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img10);
					// 400x300
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img10);
					// 164x123
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img10);
					// 133x100
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img10);
					// 75x56
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img10);
				} else {
					$img10 = '';
				}

        
				
				$sql_insert = "INSERT INTO imoveis (`finalidade`, `id_tipo`, `titulo`, `descricao`, `google_maps`, `valor_aluguel_venda`, `ap_tipo_estudio`, `qtd_dormitorios`, `qtd_suites`,
				`qtd_banheiros`, `qtd_vagas_garagem`, `area_util_m2`, `eletrodomesticos`, `mobilia`, `valor_iptu`, `valor_condominio`, `nome_condominio_predio`, `qtd_pavimentos`,
				`qtd_elevadores`, `qtd_blocos`, `salao_festas`, `piscina`, `seguranca`, `hobby_box`, `img1`, `img2`, `img3`, `img4`, `img5`, `ativo`, `obs`, `internet`, `img6`, `img7`, `img8`, `img9`, `img10`, `obs_condominio`, `endereco1`, `endereco2`) VALUES ('$finalidade', '$id_tipo' ,
				'$titulo', '$descricao', '$google_maps', '$valor_aluguel_venda', '$ap_tipo_estudio', '$qtd_dormitorios', '$qtd_suites', '$qtd_banheiros', '$qtd_vagas_garagem',
				'$area_util_m2', '$eletrodomesticos', '$mobilia', '$valor_iptu' , '$valor_condominio', '$nome_condominio_predio', '$qtd_pavimentos', '$qtd_elevadores', '$qtd_blocos',
				'$salao_festas', '$piscina', '$seguranca', '$hobby_box', '$img1', '$img2', '$img3', '$img4', '$img5', '$ativo', '$obs', '$internet', '$img6', '$img7', '$img8', '$img9', '$img10', '$obs_condominio', '$endereco1', '$endereco2')";
				$exe_insert = mysql_query($sql_insert, $base) or aw_error(mysql_error());
				
				unset($_POST);
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_incluir" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Incluir" method="post" onsubmit="return performCheck('frm_incluir', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td class="grid_topo" height="20" colspan="2">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td class="bg_incluir" colspan="2">
            <?
			erro_bloco($erro);
            ?>
            </td>
        </tr>
            <?
		}
		?>
		<?
        if (isset($sucesso)):
        ?>
        <tr>
          <td class="bg_incluir" colspan="2">
            <ul id="sucesso_sistema">
                <li>Imóvel cadastrado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
		<tr>
        <td class="bg_incluir" colspan="2">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Finalidade *<br />
            &nbsp;&nbsp;<select name="finalidade">
                        <?php
                        $ar_finalidade = array(
                          '' => '',
                          1  => 'Locação',
                          2  => 'Venda'
                        );
                        foreach($ar_finalidade as $fk => $fv){
                          if (@$_POST['finalidade'] == $fk){
                          ?>
                          <option value="<?=$fk?>" selected="selected"><?=$fv?> </option>
                          <?
                          } else {
                          ?>
                          <option value="<?=$fk?>"><?=$fv?> </option>
                          <?  
                          }
                        }
                        ?>
                        </select>
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Tipo *<br />
            &nbsp;&nbsp;<select name="id_tipo">
                        <option value=""></option>
                        <?php
                        $sql_tipos = "SELECT * FROM tipos ORDER BY tipo ASC";
                        $exe_tipos = mysql_query($sql_tipos, $base) or aw_error(mysql_error());
                        $num_tipos = mysql_num_rows($exe_tipos);
                        if ($num_tipos > 0){
                          while ($reg_tipos = mysql_fetch_array($exe_tipos, MYSQL_ASSOC)){
                            if (@$_POST['id_tipo'] == $reg_tipos['id_tipo']){
                            ?>
                            <option value="<?=$reg_tipos['id_tipo']?>" selected="selected"><?=stripslashes($reg_tipos['tipo'])?></option>
                            <?
                            } else {
                            ?>
                            <option value="<?=$reg_tipos['id_tipo']?>"><?=stripslashes($reg_tipos['tipo'])?></option>
                            <?
                            }
                          }
                        }
                        
                        ?>
                        </select>
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Título *<br />
            &nbsp;&nbsp;<input name="titulo" type="text" id="titulo" size="50" maxlength="100" value="<?php echo @stripslashes($_POST['titulo'])?>" />
           </td>
        </tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Endereço 1<br />
            &nbsp;&nbsp;<input name="endereco1" type="text" id="endereco1" size="50" maxlength="250" value="<?php echo @stripslashes($_POST['endereco1'])?>" />
           </td>
					 <td class="bg_incluir">
            Endereço 2<br />
            <input name="endereco2" type="text" id="endereco2" size="50" maxlength="250" value="<?php echo @stripslashes($_POST['endereco2'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Descrição *<br />
            &nbsp;&nbsp;<textarea name="descricao" id="descricao" style="width: 300px; height: 100px;"><?php echo @stripslashes($_POST['descricao'])?></textarea>
           </td>
					 <td class="bg_incluir">
            Localização (código incorporado <a href="https://maps.google.com.br" target="_blank">Google Maps</a>) *<br />
            <textarea name="google_maps" id="google_maps" style="width: 300px; height: 100px;"><?php echo @stripslashes($_POST['google_maps'])?></textarea>
           </td>
        </tr>

				<tr>
					 <td class="bg_incluir">
            &nbsp;&nbsp;Valor do aluguel / Valor de venda *<br />
            &nbsp;&nbsp;<input name="valor_aluguel_venda" type="text" id="valor_aluguel_venda" size="12" maxlength="100" value="<?php echo @stripslashes($_POST['valor_aluguel_venda'])?>" />
           </td>
					 <td class="bg_incluir">
            Internet<br />
            <input name="internet" type="text" id="titulo" size="30" maxlength="20" value="<?php echo @stripslashes($_POST['internet'])?>" />
           </td>
        </tr>
        
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Apartamento tipo estúdio<br />
            &nbsp;&nbsp;<select name="ap_tipo_estudio">
												<?
												$ar_ativo = array(''=>'', 'S'=>'Sim', 'N'=>'Não');
												foreach ($ar_ativo as $c => $v){
													if (@$_POST['ap_tipo_estudio'] == $c){
													?>
													<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp;&nbsp;</option>
													<?
													} else {
													?>
													<option value="<?=$c?>"><?=$v?>&nbsp;&nbsp;</option>
													<?  
													}
												}
												?>
												</select>
           </td>
					 <td class="bg_incluir">
            Quantidade de domitórios<br />
            <input name="qtd_dormitorios" type="text" id="qtd_dormitorios" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_dormitorios'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Quantidade de suítes<br />
            &nbsp;&nbsp;<input name="qtd_suites" type="text" id="qtd_suites" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_suites'])?>" />
           </td>
					 <td class="bg_incluir">
            Quantidade de bainheiros<br />
            <input name="qtd_banheiros" type="text" id="qtd_banheiros" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_banheiros'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Vagas na garagem<br />
            &nbsp;&nbsp;<?=input_select('qtd_vagas_garagem', array(''=>'', 'S'=>'Sim','N'=>'Não', 'O' => 'Opcional', 1 => 1, 2 => 2, 3 => 3))?>
           </td>
					 <td class="bg_incluir">
            Área útil em m<sup>2</sup><br />
            <input name="area_util_m2" type="text" id="area_util_m2" size="6" maxlength="4" value="<?php echo @stripslashes($_POST['area_util_m2'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
             &nbsp;&nbsp;Eletrodomésticos<br />
						 &nbsp;&nbsp;<?=input_select('eletrodomesticos', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
					 <td class="bg_incluir">
             Mobília <br />
						 <?=input_select('mobilia', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Valor do IPTU<br />
            &nbsp;&nbsp;<input name="valor_iptu" type="text" id="valor_iptu" size="12" maxlength="20" value="<?php echo @stripslashes($_POST['valor_iptu'])?>" />
           </td>
					 <td class="bg_incluir">
						Valor do condomínio / Caracter&iacute;sticas do condom&iacute;nio<br />
						<input name="valor_condominio" type="text" id="valor_condominio" size="12" maxlength="50" value="<?php echo @stripslashes($_POST['valor_condominio'])?>" />
						<input name="obs_condominio" type="text" id="obs_condominio" size="30" value="<?php echo @stripslashes($_POST['obs_condominio'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Nome do condomínio / Prédio<br />
            &nbsp;&nbsp;<input name="nome_condominio_predio" type="text" id="nome_condominio_predio" size="40" maxlength="200" value="<?php echo @stripslashes($_POST['nome_condominio_predio'])?>" />
           </td>
					 <td class="bg_incluir">
						Quantidade de pavimentos<br />
						<input name="qtd_pavimentos" type="text" id="qtd_pavimentos" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_pavimentos'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Quantidade de elevadores<br />
            &nbsp;&nbsp;<input name="qtd_elevadores" type="text" id="qtd_elevadores" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_elevadores'])?>" />
           </td>
					 <td class="bg_incluir">
						Quantidade de blocos<br />
						<input name="qtd_blocos" type="text" id="qtd_blocos" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_blocos'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Salão de festas<br />
            &nbsp;&nbsp;<?=input_select('salao_festas', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>
           </td>
					 <td class="bg_incluir">
						Piscina<br />
						<?=input_select('piscina', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Segurança<br />
            &nbsp;&nbsp;<?=input_select('seguranca', array(''=>'', 'S'=>'Sim','N'=>'Não', 'H'=>'24H'))?>
           </td>
					 <td class="bg_incluir">
						Hobby box<br />
						<?=input_select('hobby_box', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Imagem 1 *<br />
            &nbsp;&nbsp;<input type="file" name="img1" size="30" />
           </td>
					 <td class="bg_incluir">
            Imagem 2<br />
            <input type="file" name="img2" size="30" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Imagem 3<br />
            &nbsp;&nbsp;<input type="file" name="img3" size="30" />
           </td>
					 <td class="bg_incluir">
            Imagem 4<br />
            <input type="file" name="img4" size="30" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Imagem 5<br />
            &nbsp;&nbsp;<input type="file" name="img5" size="30" />
           </td>
					 <td class="bg_incluir">
						Imagem 6<br />
            <input type="file" name="img6" size="30" />
					 </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Imagem 7<br />
            &nbsp;&nbsp;<input type="file" name="img7" size="30" />
           </td>
					 <td class="bg_incluir">
						Imagem 8<br />
            <input type="file" name="img8" size="30" />
					 </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Imagem 9<br />
            &nbsp;&nbsp;<input type="file" name="img9" size="30" />
           </td>
					 <td class="bg_incluir">
						Imagem 10<br />
            <input type="file" name="img10" size="30" />
					 </td>
				</tr>
				
        <tr>
						<td class="bg_incluir">
            &nbsp;&nbsp;Observações<br />
            &nbsp;&nbsp;<input name="obs" type="text" id="obs" size="53" value="<?php echo @stripslashes($_POST['obs'])?>" />
           </td>
           <td class="bg_incluir">
            Ativo *<br />
            <?=input_select('ativo', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>    
           </td>
        </tr>
				
        </tr>
        <tr>
        <td class="grid_topo" height="30" align="center" colspan="2">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
      $(document).ready(function(){
        $("#valor_aluguel_venda").maskMoney({
          thousands: '.',
          decimal: ',',  
        });  
      });
		</script>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			
			// verificando se aquele id existe
			$sql_ver = "SELECT count(*) as tem FROM imoveis WHERE id_imovel = '".normaltxt($_POST['registro'])."'";
			$exe_ver = mysql_query($sql_ver, $base) or aw_error(mysql_error());
			$reg_ver = mysql_fetch_array($exe_ver, MYSQL_ASSOC);
			if ($reg_ver['tem'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;

			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM imoveis WHERE id_imovel = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
			
			
			$_POST['id_imovel'] 							= stripslashes($reg_select['id_imovel']);
			$_POST['finalidade'] 							= stripslashes($reg_select['finalidade']);
			$_POST['id_tipo']	 								= stripslashes($reg_select['id_tipo']);
			$_POST['titulo'] 									= stripslashes($reg_select['titulo']);
			$_POST['descricao'] 							= stripslashes($reg_select['descricao']);
			$_POST['google_maps'] 						= stripslashes(unhtmlentities($reg_select['google_maps']));
			$_POST['valor_aluguel_venda'] 		= number_format($reg_select['valor_aluguel_venda'],2,",",".");
			$_POST['ap_tipo_estudio'] 				= stripslashes($reg_select['ap_tipo_estudio']);
			$_POST['qtd_dormitorios'] 				= $reg_select['qtd_dormitorios'] > 0 ? stripslashes($reg_select['qtd_dormitorios']) : '';
			$_POST['qtd_suites'] 							= $reg_select['qtd_suites'] > 0 ? stripslashes($reg_select['qtd_suites']) : '';
			$_POST['qtd_banheiros'] 					= $reg_select['qtd_banheiros'] > 0 ? stripslashes($reg_select['qtd_banheiros']) : '';
			$_POST['qtd_vagas_garagem'] 			= stripslashes($reg_select['qtd_vagas_garagem']);
			$_POST['area_util_m2'] 						= $reg_select['area_util_m2'] > 0 ? stripslashes($reg_select['area_util_m2']) : '';
			$_POST['eletrodomesticos'] 				= stripslashes($reg_select['eletrodomesticos']);
			$_POST['mobilia'] 								= stripslashes($reg_select['mobilia']);
			$_POST['valor_iptu'] 							= stripslashes($reg_select['valor_iptu']);
			$_POST['valor_condominio'] 				= stripslashes($reg_select['valor_condominio']);
			$_POST['nome_condominio_predio'] 	= stripslashes($reg_select['nome_condominio_predio']);
			$_POST['qtd_pavimentos'] 					= $reg_select['qtd_pavimentos'] > 0 ? stripslashes($reg_select['qtd_pavimentos']) : '';
			$_POST['qtd_elevadores'] 					= $reg_select['qtd_elevadores'] > 0 ? stripslashes($reg_select['qtd_elevadores']) : '';
			$_POST['qtd_blocos'] 							= $reg_select['qtd_blocos'] > 0 ? stripslashes($reg_select['qtd_blocos']) : '';
			$_POST['salao_festas'] 						= stripslashes($reg_select['salao_festas']);
			$_POST['piscina'] 								= stripslashes($reg_select['piscina']);
			$_POST['seguranca'] 							= stripslashes($reg_select['seguranca']);
			$_POST['hobby_box'] 							= stripslashes($reg_select['hobby_box']);
			$_POST['img1'] 										= stripslashes($reg_select['img1']);
			$_POST['img2']	 									= stripslashes($reg_select['img2']);
			$_POST['img3'] 										= stripslashes($reg_select['img3']);
			$_POST['img4'] 										= stripslashes($reg_select['img4']);
			$_POST['img5']	 									= stripslashes($reg_select['img5']);
			$_POST['ativo'] 									= stripslashes($reg_select['ativo']);
			$_POST['obs'] 										= stripslashes($reg_select['obs']);
			$_POST['internet'] 								= stripslashes($reg_select['internet']);
			$_POST['img6']	 									= stripslashes($reg_select['img6']);
			$_POST['img7']	 									= stripslashes($reg_select['img7']);
			$_POST['img8']	 									= stripslashes($reg_select['img8']);
			$_POST['img9']	 									= stripslashes($reg_select['img9']);
			$_POST['img10']	 									= stripslashes($reg_select['img10']);
			$_POST['obs_condominio']	 				= stripslashes($reg_select['obs_condominio']);
			
			$_POST['endereco1']	 							= stripslashes($reg_select['endereco1']);
			$_POST['endereco2']	 							= stripslashes($reg_select['endereco2']);
		
		} else {
			// validando
			// id_imovel
			if (isset($_POST['id_imovel'])){
				if (empty($_POST['id_imovel']) || !is_numeric($_POST['id_imovel'])){
					$erro[] = 'Erro ao alterar.';
				}
			} else {
				$erro[] = 'Erro ao alterar.';
			}
			// validando finalidade
      if (isset($_POST['finalidade'])){
        if (empty($_POST['finalidade'])){
          $erro[] = 'Informe a finalidade.';
        } elseif (!in_array($_POST['finalidade'], array(1,2))){
          $erro[] = 'Informe a finalidade.';
        }
      } else {
        $erro[] = 'Informe a finalidade.';
      }
      // validando id_tipo
      if (isset($_POST['id_tipo'])){
        if (empty($_POST['id_tipo'])){
          $erro[] = 'Informe o tipo de imóvel.';
        } else {
          $id_tipo = normaltxt($_POST['id_tipo']);
          $sql_tem_tipo = "SELECT COUNT(1) AS tem_tipo FROM tipos WHERE id_tipo = '$id_tipo'";
          $exe_tem_tipo = mysql_query($sql_tem_tipo, $base) or aw_error(mysql_error());
          $reg_tem_tipo = mysql_fetch_array($exe_tem_tipo, MYSQL_ASSOC);
          if ($reg_tem_tipo['tem_tipo'] == 0){
            $erro[] = 'O tipo de imóvel escolhido não existe.';
          }
        }
      } else {
        $erro[] = 'Informe o tipo de imóvel.';
      }
      // validando titulo
      if (isset($_POST['titulo'])){
        if (empty($_POST['titulo'])){
          $erro[] = 'Informe o título.';
        } elseif (strlen($_POST['titulo']) > 100){
          $erro[] = 'O título deve conter no máximo 100 caracteres.';
        }
      } else {
        $erro[] = 'Informe o título.';
      }
			// validando endereco1
			if (isset($_POST['endereco1'])){
				if (!empty($_POST['endereco1']) && strlen($_POST['endereco1']) > 250){
					$erro[] = 'O endereço 1 deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o endereço 1.';
			}
			// validando endereco2
			if (isset($_POST['endereco2'])){
				if (!empty($_POST['endereco2']) && strlen($_POST['endereco2']) > 250){
					$erro[] = 'O endereço 2 deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o endereço 2.';
			}
      // validando descricao
      if (isset($_POST['descricao'])){
        if (empty($_POST['descricao'])){
          $erro[] = 'Informe a descrição.';
        } elseif (strlen($_POST['descricao']) > 500){
          $erro[] = 'A descrição deve conter no máximo 500 caracteres.';
        }
      } else {
        $erro[] = 'Informe a descrição.';
      }
      // validando google_maps
      if (isset($_POST['google_maps'])){
        if (empty($_POST['google_maps'])){
          $erro[] = 'Informe o código do Google Maps.';
        } elseif (strlen($_POST['google_maps']) > 5000){
          $erro[] = 'O código do Google Maps está muito grande.';
        }
      } else {
        $erro[] = 'Informe o código do Google Maps.';
      }
      // validando valor_aluguel_venda
      if (isset($_POST['valor_aluguel_venda'])){
        if (empty($_POST['valor_aluguel_venda'])){
          $erro[] = 'Informe o valor de aluguel / venda.';
        } elseif (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['valor_aluguel_venda'])) {
          $erro[] = 'O valor de aluguel / venda está incorreto.';
        }
      } else {
        $erro[] = 'Informe o valor de aluguel / venda.';
      }
			// validando internet
			if (isset($_POST['internet'])){
				
			} else {
				$erro[] = 'Informe sobre a internet.';
			}
			// validando ap_tipo_estudio
			if (isset($_POST['ap_tipo_estudio'])){
				if (!empty($_POST['ap_tipo_estudio'])){
					if (!in_array($_POST['ap_tipo_estudio'], array('','S','N'))) {
						$erro[] = 'Informe se o apartamento é do tipo estúdio.';
					}
				}
			} else {
				$erro[] = 'Informe se o apartamento é do tipo estúdio.';
			}
			// validando qtd_dormitorios
			if (isset($_POST['qtd_dormitorios'])){
				if (!empty($_POST['qtd_dormitorios']) && strlen($_POST['qtd_dormitorios']) > 100){
					$erro[] = 'A quantidade de dormitorios deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de domitórios.';
			}
			// validando qtd_suites
			if (isset($_POST['qtd_suites'])){
				if (!empty($_POST['qtd_suites']) && strlen($_POST['qtd_suites']) > 100){
					$erro[] = 'A quantidade de suítes deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de suítes.';
			}
			// validando qtd_banheiros
			if (isset($_POST['qtd_banheiros'])){
				if (!empty($_POST['qtd_banheiros']) && strlen($_POST['qtd_banheiros']) > 100){
					$erro[] = 'A quantidade de banheiros deve conter no máximo 100 caracteres.';
				}
			} else {
				$erro[] = 'Informe a quantidade de banheiros.';
			}
			// validando qtd_vagas_garagem
      if (isset($_POST['qtd_vagas_garagem'])){
				if (!empty($_POST['qtd_vagas_garagem'])){
					if (!in_array($_POST['qtd_vagas_garagem'], array('S','N','O', 1, 2, 3))){
						$erro[] = 'A quantidade sobre a vaga na garagem.';
					}
				}
			} else {
				$erro[] = 'A quantidade sobre a vaga na garagem.';
			}
			// validando area_util_m2
			if (isset($_POST['area_util_m2'])){
				if (!empty($_POST['area_util_m2'])){
					if (!is_numeric($_POST['area_util_m2'])){
						$erro[] = 'A área útil deve ser numérica.';
					}
				}
			} else {
				$erro[] = 'Informe a área útil em m<sup>2</sup>.';
			}
			// validando eletrodomesticos
			if (isset($_POST['eletrodomesticos'])){
				if (!empty($_POST['eletrodomesticos']) && strlen($_POST['eletrodomesticos']) > 2000){
					$erro[] = 'Descrição dos eletrodomésticos com no máximo 2000 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição dos eletrodomésticos.';
			}
			// validando mobilia
			if (isset($_POST['mobilia'])){
				if (!empty($_POST['mobilia']) && strlen($_POST['mobilia']) > 2000){
					$erro[] = 'Descrição das mobílias com no máximo 2000 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição das mobílias.';
			}
			// validar valor_iptu
			if (isset($_POST['valor_iptu'])){
        
      } else {
        $erro[] = 'Informe o valor do IPTU.';
      }
			// validar valor_condominio
			if (isset($_POST['valor_condominio'])){
        if (!empty($_POST['valor_condominio']) && strlen($_POST['valor_condominio']) > 50){
          $erro[] = 'Valor do condomínio com no máximo 50 caracteres.';
				}
      } else {
        $erro[] = 'Informe o valor do condomínio.';
      }
			// validando obs_condominio
			if (isset($_POST['obs_condominio'])){
				
			} else {
				$erro[] = 'Informe as observações do condomínio.';
			}
			// validando nome_condominio_predio
			if (isset($_POST['nome_condominio_predio'])){
				if (!empty($_POST['nome_condominio_predio']) && strlen($_POST['nome_condominio_predio']) > 200){
					$erro[] = 'Nome do condomínio / prédio com no máximo 200 caracteres.';
				}
			} else {
				$erro[] = 'Informe o nome do condomínio / prédio.';
			}
			// validando qtd_pavimentos
			if (isset($_POST['qtd_pavimentos'])){
        if (!empty($_POST['qtd_pavimentos'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_pavimentos'])) {
					  $erro[] = 'A quantidade de pavimentos está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de pavimentos.';
      }
			// validando qtd_elevadores
			if (isset($_POST['qtd_elevadores'])){
        if (!empty($_POST['qtd_elevadores'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_elevadores'])) {
					  $erro[] = 'A quantidade de elevadores está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de elevadores.';
      }
			// validando qtd_blocos
			if (isset($_POST['qtd_blocos'])){
        if (!empty($_POST['qtd_blocos'])){
          if (!preg_match('/^\d{1,3}(\.\d{3})*(\,\d{2})?$/', $_POST['qtd_blocos'])) {
					  $erro[] = 'A quantidade de blocos está incorreta.';
					}
				}
      } else {
        $erro[] = 'Informe a quantidade de blocos.';
      }
			// validando salao_festas
			if (isset($_POST['salao_festas'])){
				if (!empty($_POST['salao_festas'])){
					if (!in_array($_POST['salao_festas'], array('S', 'N'))){
						$erro[] = 'Informe se possui salão de festas.';
					}
				}
			} else {
				$erro[] = 'Informe se possui salão de festas.';
			}
			// validando piscina
			if (isset($_POST['piscina'])){
				if (!empty($_POST['piscina'])){
					if (!in_array($_POST['piscina'], array('S', 'N'))){
						$erro[] = 'Informe se possui piscina.';
					}
				}
			} else {
				$erro[] = 'Informe se possui piscina.';
			}
			// validando seguranca
			if (isset($_POST['seguranca'])){
				if (!empty($_POST['seguranca'])){
					if (!in_array($_POST['seguranca'], array('S', 'N', 'H'))){
						$erro[] = 'Informe se possui seguranças.';
					}
				}
			} else {
				$erro[] = 'Informe se possui seguranças.';
			}
			// validando hobby_box
			if (isset($_POST['hobby_box'])){
				if (!empty($_POST['hobby_box'])){
					if (!in_array($_POST['hobby_box'], array('S', 'N', 'O'))){
						$erro[] = 'Informe se possui hobby box.';
					}
				}
			} else {
				$erro[] = 'Informe se possui hobby box.';
			}
			// img1
			if (isset($_FILES['img1']['name'], $_FILES['img1']['size'], $_FILES['img1']['tmp_name'], $_FILES['img1']['type'])){
				if (!empty($_FILES['img1']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img1']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 1 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img1']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 1 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img1']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img1']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img1']['type']),-3) != 'gif' && substr(strtolower($_FILES['img1']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 1 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img1']['tmp_name']) || is_dir($_FILES['img1']['name']) || getimagesize($_FILES['img1']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 1 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img1']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 1 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 1.";
			}
			// img2
			if (isset($_FILES['img2']['name'], $_FILES['img2']['size'], $_FILES['img2']['tmp_name'], $_FILES['img2']['type'])){
				if (!empty($_FILES['img2']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img2']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 2 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img2']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 2 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img2']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img2']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img2']['type']),-3) != 'gif' && substr(strtolower($_FILES['img2']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 2 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img2']['tmp_name']) || is_dir($_FILES['img2']['name']) || getimagesize($_FILES['img2']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 2 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img2']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 2 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 2.";
			}
			// img3
			if (isset($_FILES['img3']['name'], $_FILES['img3']['size'], $_FILES['img3']['tmp_name'], $_FILES['img3']['type'])){
				if (!empty($_FILES['img3']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img3']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 3 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img3']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 3 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img3']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img3']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img3']['type']),-3) != 'gif' && substr(strtolower($_FILES['img3']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 3 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img3']['tmp_name']) || is_dir($_FILES['img3']['name']) || getimagesize($_FILES['img3']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 3 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img3']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 3 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 3.";
			}
			// img4
			if (isset($_FILES['img4']['name'], $_FILES['img4']['size'], $_FILES['img4']['tmp_name'], $_FILES['img4']['type'])){
				if (!empty($_FILES['img4']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img4']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 4 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img4']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 4 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img4']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img4']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img4']['type']),-3) != 'gif' && substr(strtolower($_FILES['img4']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 4 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img4']['tmp_name']) || is_dir($_FILES['img4']['name']) || getimagesize($_FILES['img4']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 4 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img4']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 4 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 4.";
			}
			// img5
			if (isset($_FILES['img5']['name'], $_FILES['img5']['size'], $_FILES['img5']['tmp_name'], $_FILES['img5']['type'])){
				if (!empty($_FILES['img5']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img5']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 5 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img5']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 5 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img5']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img5']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img5']['type']),-3) != 'gif' && substr(strtolower($_FILES['img5']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 5 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img5']['tmp_name']) || is_dir($_FILES['img5']['name']) || getimagesize($_FILES['img5']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 5 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img5']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 5 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 5.";
			}
			// img6
			if (isset($_FILES['img6']['name'], $_FILES['img6']['size'], $_FILES['img6']['tmp_name'], $_FILES['img6']['type'])){
				if (!empty($_FILES['img6']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img6']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 6 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img6']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 6 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img6']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img6']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img6']['type']),-3) != 'gif' && substr(strtolower($_FILES['img6']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 6 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img6']['tmp_name']) || is_dir($_FILES['img6']['name']) || getimagesize($_FILES['img6']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 6 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img6']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 6 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 6.";
			}
			// img7
			if (isset($_FILES['img7']['name'], $_FILES['img7']['size'], $_FILES['img7']['tmp_name'], $_FILES['img7']['type'])){
				if (!empty($_FILES['img7']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img7']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 7 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img7']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 7 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img7']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img7']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img7']['type']),-3) != 'gif' && substr(strtolower($_FILES['img7']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 7 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img7']['tmp_name']) || is_dir($_FILES['img7']['name']) || getimagesize($_FILES['img7']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 7 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img7']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 7 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 7.";
			}
			// img8
			if (isset($_FILES['img8']['name'], $_FILES['img8']['size'], $_FILES['img8']['tmp_name'], $_FILES['img8']['type'])){
				if (!empty($_FILES['img8']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img8']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 8 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img8']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 8 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img8']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img8']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img8']['type']),-3) != 'gif' && substr(strtolower($_FILES['img8']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 8 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img8']['tmp_name']) || is_dir($_FILES['img8']['name']) || getimagesize($_FILES['img8']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 8 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img8']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 8 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 8.";
			}
			// img9
			if (isset($_FILES['img9']['name'], $_FILES['img9']['size'], $_FILES['img9']['tmp_name'], $_FILES['img9']['type'])){
				if (!empty($_FILES['img9']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img9']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 9 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img9']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 9 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img9']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img9']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img9']['type']),-3) != 'gif' && substr(strtolower($_FILES['img9']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 9 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img9']['tmp_name']) || is_dir($_FILES['img9']['name']) || getimagesize($_FILES['img9']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 9 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img9']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 9 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 9.";
			}
			// img10
			if (isset($_FILES['img10']['name'], $_FILES['img10']['size'], $_FILES['img10']['tmp_name'], $_FILES['img10']['type'])){
				if (!empty($_FILES['img10']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img10']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem 10 contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img10']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem 10 é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img10']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img10']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img10']['type']),-3) != 'gif' && substr(strtolower($_FILES['img10']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem 10 deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img10']['tmp_name']) || is_dir($_FILES['img10']['name']) || getimagesize($_FILES['img10']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem 10 é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img10']['tmp_name']);
						if ($wh[0] != $max_w || $wh[1] != $max_h){
							$erro[] = 'A imagem 10 deve ter as dimensões '.$max_w.'px de largura por '.$max_h.'px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem 10.";
			}
			// validando obs
			if (isset($_POST['obs'])){
				
			} else {
				$erro[] = 'Informe as observações.';
			}
			// validando ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo']) || !in_array($_POST['ativo'], array('S', 'N'))){
					$erro[] = 'Informe se está ou não está ativo.';
				}
			} else {
				$erro[] = 'Informe se está ou não está ativo.';
			}
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$id_imovel							= normaltxt($_POST['id_imovel']);
				$finalidade							= normaltxt($_POST['finalidade']);
				$id_tipo								= normaltxt($_POST['id_tipo']);
				$titulo									= normaltxt($_POST['titulo']);
				$descricao							= normaltxt($_POST['descricao']);
				$google_maps						= normaltxt($_POST['google_maps']);
				$valor_aluguel_venda		= formata_dinheiro($_POST['valor_aluguel_venda']);
				$ap_tipo_estudio 				= normaltxt($_POST['ap_tipo_estudio']);
				$qtd_dormitorios				= normaltxt($_POST['qtd_dormitorios']);
				$qtd_suites							= normaltxt($_POST['qtd_suites']);
				$qtd_banheiros					= normaltxt($_POST['qtd_banheiros']);
				$qtd_vagas_garagem			= normaltxt($_POST['qtd_vagas_garagem']);
				$area_util_m2						= normaltxt($_POST['area_util_m2']);
				$eletrodomesticos				= normaltxt($_POST['eletrodomesticos']);
				$mobilia								= normaltxt($_POST['mobilia']);
				$valor_iptu							= normaltxt($_POST['valor_iptu']);
				$valor_condominio				= normaltxt($_POST['valor_condominio']);
				$nome_condominio_predio	= normaltxt($_POST['nome_condominio_predio']);
				$qtd_pavimentos					= normaltxt($_POST['qtd_pavimentos']);	
				$qtd_elevadores 				= normaltxt($_POST['qtd_elevadores']);
				$qtd_blocos 						= normaltxt($_POST['qtd_blocos']);
				$salao_festas						= normaltxt($_POST['salao_festas']);
				$piscina 								= normaltxt($_POST['piscina']);
				$seguranca 							= normaltxt($_POST['seguranca']);
				$hobby_box							= normaltxt($_POST['hobby_box']);
				$ativo									= normaltxt($_POST['ativo']);
				$obs										= normaltxt($_POST['obs']);
				$internet								= normaltxt($_POST['internet']);
				$obs_condominio					= normaltxt($_POST['obs_condominio']);
				$endereco1							= normaltxt($_POST['endereco1']);
				$endereco2							= normaltxt($_POST['endereco2']);
				
				$sql_aux = '';
				
				// img1
				if (!empty($_FILES['img1']['name'])){
					$id_img1 = gen_numero('imoveis');
					if (substr($_FILES['img1']['name'],-3) == 'jpg' || substr($_FILES['img1']['name'],-3) == 'JPG'){
						$ext1 = ".jpg";
					} elseif (substr($_FILES['img1']['name'],-3) == 'gif' || substr($_FILES['img1']['name'],-3) == 'GIF'){
						$ext1 = ".gif";
					} else {
						$ext1 = ".jpg";
					}
					$img1 = $id_img1.$ext1;
					// 800x600
					$img = new hft_image($_FILES['img1']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img1);
					// 400x300
					$img = new hft_image($_FILES['img1']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img1);
					// 164x123
					$img = new hft_image($_FILES['img1']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img1);
					// 133x100
					$img = new hft_image($_FILES['img1']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img1);
					// 75x56
					$img = new hft_image($_FILES['img1']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img1);
					
					if (isset($_POST['img1'])){
						if (!empty($_POST['img1'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img1'])){
									unlink("../imoveis/".$tam."/".$_POST['img1']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img1 = '$img1'";
					$_POST['img1'] = $img1;
					
				}
				
				// img2
				if (!empty($_FILES['img2']['name'])){
					$id_img2 = gen_numero('imoveis');
					if (substr($_FILES['img2']['name'],-3) == 'jpg' || substr($_FILES['img2']['name'],-3) == 'JPG'){
						$ext2 = ".jpg";
					} elseif (substr($_FILES['img2']['name'],-3) == 'gif' || substr($_FILES['img2']['name'],-3) == 'GIF'){
						$ext2 = ".gif";
					} else {
						$ext2 = ".jpg";
					}
					$img2 = $id_img2.$ext2;
					// 800x600
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img2);
					// 400x300
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img2);
					// 164x123
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img2);
					// 133x100
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img2);
					// 75x56
					$img = new hft_image($_FILES['img2']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img2);
					
					if (isset($_POST['img2'])){
						if (!empty($_POST['img2'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img2'])){
									unlink("../imoveis/".$tam."/".$_POST['img2']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img2 = '$img2'";
					$_POST['img2'] = $img2;
				}
				
				// img3
				if (!empty($_FILES['img3']['name'])){
					$id_img3 = gen_numero('imoveis');
					if (substr($_FILES['img3']['name'],-3) == 'jpg' || substr($_FILES['img3']['name'],-3) == 'JPG'){
						$ext3 = ".jpg";
					} elseif (substr($_FILES['img3']['name'],-3) == 'gif' || substr($_FILES['img3']['name'],-3) == 'GIF'){
						$ext3 = ".gif";
					} else {
						$ext3 = ".jpg";
					}
					$img3 = $id_img3.$ext3;
					// 800x600
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img3);
					// 400x300
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img3);
					// 164x123
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img3);
					// 133x100
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img3);
					// 75x56
					$img = new hft_image($_FILES['img3']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img3);
					
					if (isset($_POST['img3'])){
						if (!empty($_POST['img3'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img3'])){
									unlink("../imoveis/".$tam."/".$_POST['img3']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img3 = '$img3'";
					$_POST['img3'] = $img3;
				}
				
				// img4
				if (!empty($_FILES['img4']['name'])){
					$id_img4 = gen_numero('imoveis');
					if (substr($_FILES['img4']['name'],-3) == 'jpg' || substr($_FILES['img4']['name'],-3) == 'JPG'){
						$ext4 = ".jpg";
					} elseif (substr($_FILES['img4']['name'],-3) == 'gif' || substr($_FILES['img4']['name'],-3) == 'GIF'){
						$ext4 = ".gif";
					} else {
						$ext4 = ".jpg";
					}
					$img4 = $id_img4.$ext4;
					// 800x600
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img4);
					// 400x300
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img4);
					// 164x123
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img4);
					// 133x100
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img4);
					// 75x56
					$img = new hft_image($_FILES['img4']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img4);
					
					if (isset($_POST['img4'])){
						if (!empty($_POST['img4'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img4'])){
									unlink("../imoveis/".$tam."/".$_POST['img4']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img4 = '$img4'";
					$_POST['img4'] = $img4;
				}
				
				// img5
				if (!empty($_FILES['img5']['name'])){
					$id_img5 = gen_numero('imoveis');
					if (substr($_FILES['img5']['name'],-3) == 'jpg' || substr($_FILES['img5']['name'],-3) == 'JPG'){
						$ext5 = ".jpg";
					} elseif (substr($_FILES['img5']['name'],-3) == 'gif' || substr($_FILES['img5']['name'],-3) == 'GIF'){
						$ext5 = ".gif";
					} else {
						$ext5 = ".jpg";
					}
					$img5 = $id_img5.$ext5;
					// 800x600
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img5);
					// 400x300
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img5);
					// 164x123
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img5);
					// 133x100
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img5);
					// 75x56
					$img = new hft_image($_FILES['img5']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img5);
					
					if (isset($_POST['img5'])){
						if (!empty($_POST['img5'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img5'])){
									unlink("../imoveis/".$tam."/".$_POST['img5']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img5 = '$img5'";
					$_POST['img5'] = $img5;
				}
				
				// img6
				if (!empty($_FILES['img6']['name'])){
					$id_img6 = gen_numero('imoveis');
					if (substr($_FILES['img6']['name'],-3) == 'jpg' || substr($_FILES['img6']['name'],-3) == 'JPG'){
						$ext6 = ".jpg";
					} elseif (substr($_FILES['img6']['name'],-3) == 'gif' || substr($_FILES['img6']['name'],-3) == 'GIF'){
						$ext6 = ".gif";
					} else {
						$ext6 = ".jpg";
					}
					$img6 = $id_img6.$ext6;
					// 800x600
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img6);
					// 400x300
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img6);
					// 164x123
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img6);
					// 133x100
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img6);
					// 75x56
					$img = new hft_image($_FILES['img6']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img6);
					
					if (isset($_POST['img6'])){
						if (!empty($_POST['img6'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img6'])){
									unlink("../imoveis/".$tam."/".$_POST['img6']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img6 = '$img6'";
					$_POST['img6'] = $img6;
				}
				
				// img7
				if (!empty($_FILES['img7']['name'])){
					$id_img7 = gen_numero('imoveis');
					if (substr($_FILES['img7']['name'],-3) == 'jpg' || substr($_FILES['img7']['name'],-3) == 'JPG'){
						$ext7 = ".jpg";
					} elseif (substr($_FILES['img7']['name'],-3) == 'gif' || substr($_FILES['img7']['name'],-3) == 'GIF'){
						$ext7 = ".gif";
					} else {
						$ext7 = ".jpg";
					}
					$img7 = $id_img7.$ext7;
					// 800x600
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img7);
					// 400x300
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img7);
					// 164x123
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img7);
					// 133x100
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img7);
					// 75x56
					$img = new hft_image($_FILES['img7']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img7);
					
					if (isset($_POST['img7'])){
						if (!empty($_POST['img7'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img7'])){
									unlink("../imoveis/".$tam."/".$_POST['img7']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img7 = '$img7'";
					$_POST['img7'] = $img7;
				}
				
				// img8
				if (!empty($_FILES['img8']['name'])){
					$id_img8 = gen_numero('imoveis');
					if (substr($_FILES['img8']['name'],-3) == 'jpg' || substr($_FILES['img8']['name'],-3) == 'JPG'){
						$ext8 = ".jpg";
					} elseif (substr($_FILES['img8']['name'],-3) == 'gif' || substr($_FILES['img8']['name'],-3) == 'GIF'){
						$ext8 = ".gif";
					} else {
						$ext8 = ".jpg";
					}
					$img8 = $id_img8.$ext8;
					// 800x600
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img8);
					// 400x300
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img8);
					// 164x123
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img8);
					// 133x100
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img8);
					// 75x56
					$img = new hft_image($_FILES['img8']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img8);
					
					if (isset($_POST['img8'])){
						if (!empty($_POST['img8'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img8'])){
									unlink("../imoveis/".$tam."/".$_POST['img8']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img8 = '$img8'";
					$_POST['img8'] = $img8;
				}
				
				// img9
				if (!empty($_FILES['img9']['name'])){
					$id_img9 = gen_numero('imoveis');
					if (substr($_FILES['img9']['name'],-3) == 'jpg' || substr($_FILES['img9']['name'],-3) == 'JPG'){
						$ext9 = ".jpg";
					} elseif (substr($_FILES['img9']['name'],-3) == 'gif' || substr($_FILES['img9']['name'],-3) == 'GIF'){
						$ext9 = ".gif";
					} else {
						$ext9 = ".jpg";
					}
					$img9 = $id_img9.$ext9;
					// 800x600
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img9);
					// 400x300
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img9);
					// 164x123
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img9);
					// 133x100
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img9);
					// 75x56
					$img = new hft_image($_FILES['img9']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img9);
					
					if (isset($_POST['img9'])){
						if (!empty($_POST['img9'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img9'])){
									unlink("../imoveis/".$tam."/".$_POST['img9']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img9 = '$img9'";
					$_POST['img9'] = $img9;
				}
				
				// img10
				if (!empty($_FILES['img10']['name'])){
					$id_img10 = gen_numero('imoveis');
					if (substr($_FILES['img10']['name'],-3) == 'jpg' || substr($_FILES['img10']['name'],-3) == 'JPG'){
						$ext10 = ".jpg";
					} elseif (substr($_FILES['img10']['name'],-3) == 'gif' || substr($_FILES['img10']['name'],-3) == 'GIF'){
						$ext10 = ".gif";
					} else {
						$ext10 = ".jpg";
					}
					$img10 = $id_img10.$ext10;
					// 800x600
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(800,600,"-");
					$img->output_resized("../imoveis/800x600/".$img10);
					// 400x300
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(400,300,"-");
					$img->output_resized("../imoveis/400x300/".$img10);
					// 164x123
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(164, 123,"-");
					$img->output_resized("../imoveis/164x123/".$img10);
					// 133x100
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(133, 100,"-");
					$img->output_resized("../imoveis/133x100/".$img10);
					// 75x56
					$img = new hft_image($_FILES['img10']['tmp_name']);
					$img->resize(75, 56,"-");
					$img->output_resized("../imoveis/75x56/".$img10);
					
					if (isset($_POST['img10'])){
						if (!empty($_POST['img10'])){
							foreach($ar_tamanhos as $tam){
								if (file_exists("../imoveis/".$tam."/".$_POST['img10'])){
									unlink("../imoveis/".$tam."/".$_POST['img10']);
								}  
							}
						}
					}
					
					$sql_aux .= ", img10 = '$img10'";
					$_POST['img10'] = $img10;
				}
			
				
				$sql_update = "UPDATE imoveis SET 
                        finalidade 							= '$finalidade', 
												id_tipo 								= '$id_tipo', 
												titulo 									= '$titulo',
												descricao 							= '$descricao',
												google_maps 						= '$google_maps',
												valor_aluguel_venda 		= '$valor_aluguel_venda',
												ap_tipo_estudio 				= '$ap_tipo_estudio',
												qtd_dormitorios 				= '$qtd_dormitorios',
												qtd_suites 							= '$qtd_suites',
												qtd_banheiros 					= '$qtd_banheiros',
												qtd_vagas_garagem 			= '$qtd_vagas_garagem',
												area_util_m2 						= '$area_util_m2',
												eletrodomesticos 				= '$eletrodomesticos',
												mobilia 								= '$mobilia',
												valor_iptu 							= '$valor_iptu',
												valor_condominio 				= '$valor_condominio',
												nome_condominio_predio 	= '$nome_condominio_predio',
												qtd_pavimentos 					= '$qtd_pavimentos',
												qtd_elevadores 					= '$qtd_elevadores',
												qtd_blocos 							= '$qtd_blocos',
												salao_festas 						= '$salao_festas',
												piscina 								= '$piscina',
												seguranca 							= '$seguranca',
												hobby_box 							= '$hobby_box',
												ativo 									= '$ativo',
												obs											= '$obs',
												internet								= '$internet',
												obs_condominio					= '$obs_condominio',
												endereco1								= '$endereco1',
												endereco2								= '$endereco2' 
												$sql_aux
                        WHERE
                        id_imovel        				= '$id_imovel'
                        ";
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_imovel" value="<?php echo @$_POST['id_imovel'] ?>" />
		<input type="hidden" name="img1" value="<?php echo @$_POST['img1'] ?>" />
		<input type="hidden" name="img2" value="<?php echo @$_POST['img2'] ?>" />
		<input type="hidden" name="img3" value="<?php echo @$_POST['img3'] ?>" />
		<input type="hidden" name="img4" value="<?php echo @$_POST['img4'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img5'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img6'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img7'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img8'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img9'] ?>" />
		<input type="hidden" name="img5" value="<?php echo @$_POST['img10'] ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td class="grid_topo" height="20" colspan="2">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td class="bg_incluir" colspan="2">
            <?
			erro_bloco($erro);
            ?>
            </td>
        </tr>
            <?
		}
		?>
		<?
        if (isset($sucesso)):
        ?>
        <tr>
          <td class="bg_incluir" colspan="2">
            <ul id="sucesso_sistema">
                <li>Imóvel alterado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
		<tr>
        <td class="bg_incluir" colspan="2">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Finalidade *<br />
            &nbsp;&nbsp;<select name="finalidade">
                        <?php
                        $ar_finalidade = array(
                          '' => '',
                          1  => 'Locação',
                          2  => 'Venda'
                        );
                        foreach($ar_finalidade as $fk => $fv){
                          if (@$_POST['finalidade'] == $fk){
                          ?>
                          <option value="<?=$fk?>" selected="selected"><?=$fv?> </option>
                          <?
                          } else {
                          ?>
                          <option value="<?=$fk?>"><?=$fv?> </option>
                          <?  
                          }
                        }
                        ?>
                        </select>
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Tipo *<br />
            &nbsp;&nbsp;<select name="id_tipo">
                        <option value=""></option>
                        <?php
                        $sql_tipos = "SELECT * FROM tipos ORDER BY tipo ASC";
                        $exe_tipos = mysql_query($sql_tipos, $base) or aw_error(mysql_error());
                        $num_tipos = mysql_num_rows($exe_tipos);
                        if ($num_tipos > 0){
                          while ($reg_tipos = mysql_fetch_array($exe_tipos, MYSQL_ASSOC)){
                            if (@$_POST['id_tipo'] == $reg_tipos['id_tipo']){
                            ?>
                            <option value="<?=$reg_tipos['id_tipo']?>" selected="selected"><?=stripslashes($reg_tipos['tipo'])?></option>
                            <?
                            } else {
                            ?>
                            <option value="<?=$reg_tipos['id_tipo']?>"><?=stripslashes($reg_tipos['tipo'])?></option>
                            <?
                            }
                          }
                        }
                        
                        ?>
                        </select>
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Título *<br />
            &nbsp;&nbsp;<input name="titulo" type="text" id="titulo" size="50" maxlength="100" value="<?php echo @stripslashes($_POST['titulo'])?>" />
           </td>
        </tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Endereço 1<br />
            &nbsp;&nbsp;<input name="endereco1" type="text" id="endereco1" size="50" maxlength="250" value="<?php echo @stripslashes($_POST['endereco1'])?>" />
           </td>
					 <td class="bg_incluir">
            Endereço 2<br />
            <input name="endereco2" type="text" id="endereco2" size="50" maxlength="250" value="<?php echo @stripslashes($_POST['endereco2'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Descrição *<br />
            &nbsp;&nbsp;<textarea name="descricao" id="descricao" style="width: 300px; height: 100px;"><?php echo @stripslashes($_POST['descricao'])?></textarea>
           </td>
					 <td class="bg_incluir">
            Localização (código incorporado <a href="https://maps.google.com.br" target="_blank">Google Maps</a>) *<br />
            <textarea name="google_maps" id="google_maps" style="width: 300px; height: 100px;"><?php echo @stripslashes($_POST['google_maps'])?></textarea>
           </td>
        </tr>

				<tr>
					 <td class="bg_incluir">
            &nbsp;&nbsp;Valor do aluguel / Valor de venda *<br />
            &nbsp;&nbsp;<input name="valor_aluguel_venda" type="text" id="valor_aluguel_venda" size="12" maxlength="100" value="<?php echo @stripslashes($_POST['valor_aluguel_venda'])?>" />
           </td>
					 <td class="bg_incluir">
            Internet<br />
            <input name="internet" type="text" id="titulo" size="30" maxlength="20" value="<?php echo @stripslashes($_POST['internet'])?>" />
           </td>
        </tr>
        
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Apartamento tipo estúdio<br />
            &nbsp;&nbsp;<select name="ap_tipo_estudio">
												<?
												$ar_ativo = array(''=>'', 'S'=>'Sim', 'N'=>'Não');
												foreach ($ar_ativo as $c => $v){
													if (@$_POST['ap_tipo_estudio'] == $c){
													?>
													<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp;&nbsp;</option>
													<?
													} else {
													?>
													<option value="<?=$c?>"><?=$v?>&nbsp;&nbsp;</option>
													<?  
													}
												}
												?>
												</select>
           </td>
					 <td class="bg_incluir">
            Quantidade de domitórios<br />
            <input name="qtd_dormitorios" type="text" id="qtd_dormitorios" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_dormitorios'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Quantidade de suítes<br />
            &nbsp;&nbsp;<input name="qtd_suites" type="text" id="qtd_suites" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_suites'])?>" />
           </td>
					 <td class="bg_incluir">
            Quantidade de bainheiros<br />
            <input name="qtd_banheiros" type="text" id="qtd_banheiros" size="30" maxlength="100" value="<?php echo @stripslashes($_POST['qtd_banheiros'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Vagas na garagem<br />
            &nbsp;&nbsp;<?=input_select('qtd_vagas_garagem', array(''=>'', 'S'=>'Sim','N'=>'Não', 'O' => 'Opcional', 1 => 1, 2 => 2, 3 => 3))?>
           </td>
					 <td class="bg_incluir">
            Área útil em m<sup>2</sup><br />
            <input name="area_util_m2" type="text" id="area_util_m2" size="6" maxlength="4" value="<?php echo @stripslashes($_POST['area_util_m2'])?>" />
           </td>
				</tr>
				<tr>
           <td class="bg_incluir">
             &nbsp;&nbsp;Eletrodomésticos<br />
						 &nbsp;&nbsp;<?=input_select('eletrodomesticos', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
					 <td class="bg_incluir">
             Mobília <br />
						 <?=input_select('mobilia', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Valor do IPTU<br />
            &nbsp;&nbsp;<input name="valor_iptu" type="text" id="valor_iptu" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['valor_iptu'])?>" />
           </td>
					 <td class="bg_incluir">
						Valor do condomínio / Observações<br />
						<input name="valor_condominio" type="text" id="valor_condominio" size="12" maxlength="50" value="<?php echo @stripslashes($_POST['valor_condominio'])?>" />
						<input name="obs_condominio" type="text" id="obs_condominio" size="30" value="<?php echo @stripslashes($_POST['obs_condominio'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Nome do condomínio / Prédio<br />
            &nbsp;&nbsp;<input name="nome_condominio_predio" type="text" id="nome_condominio_predio" size="40" maxlength="200" value="<?php echo @stripslashes($_POST['nome_condominio_predio'])?>" />
           </td>
					 <td class="bg_incluir">
						Quantidade de pavimentos<br />
						<input name="qtd_pavimentos" type="text" id="qtd_pavimentos" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_pavimentos'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Quantidade de elevadores<br />
            &nbsp;&nbsp;<input name="qtd_elevadores" type="text" id="qtd_elevadores" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_elevadores'])?>" />
           </td>
					 <td class="bg_incluir">
						Quantidade de blocos<br />
						<input name="qtd_blocos" type="text" id="qtd_blocos" size="12" maxlength="12" value="<?php echo @stripslashes($_POST['qtd_blocos'])?>" />
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Salão de festas<br />
            &nbsp;&nbsp;<?=input_select('salao_festas', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>
           </td>
					 <td class="bg_incluir">
						Piscina<br />
						<?=input_select('piscina', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Segurança<br />
            &nbsp;&nbsp;<?=input_select('seguranca', array(''=>'', 'S'=>'Sim','N'=>'Não', 'H'=>'24H'))?>
           </td>
					 <td class="bg_incluir">
						Hobby box<br />
						<?=input_select('hobby_box', array(''=>'', 'O' => 'Opcional', 'S'=>'Sim','N'=>'Não'))?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir" valign="top">
						<?php
						if (!empty($_POST['img1']) && file_exists("../imoveis/133x100/".$_POST['img1'])){
						?>
						&nbsp;&nbsp;Imagem 1 *<br />
            &nbsp;&nbsp;<input type="file" name="img1" size="30" />
						<div style="margin: 10px"><img src="../imoveis/133x100/<?=$_POST['img1']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 1 *<br />
            &nbsp;&nbsp;<input type="file" name="img1" size="30" />
						<?	
						}
						?>
           </td>
					 <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img2']) && file_exists("../imoveis/133x100/".$_POST['img2'])){
						?>
						Imagem 2<br />
            <input type="file" name="img2" size="30" /> <a href="javascript: remover_imagem_imovel(2, <?=$_POST['id_imovel']?>)" id="link_img_2">[remover imagem]</a>
						<div style="margin-top: 10px" id="img2"><img src="../imoveis/133x100/<?=$_POST['img2']?>" /></div>
						<?
						} else {
						?>
						Imagem 2<br />
            <input type="file" name="img2" size="30" />
						<?	
						}
						?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img3']) && file_exists("../imoveis/133x100/".$_POST['img3'])){
						?>
						&nbsp;&nbsp;Imagem 3<br />
            &nbsp;&nbsp;<input type="file" name="img3" size="30" /> <a href="javascript: remover_imagem_imovel(3, <?=$_POST['id_imovel']?>)" id="link_img_3">[remover imagem]</a>
						<div style="margin: 10px" id="img3"><img src="../imoveis/133x100/<?=$_POST['img3']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 3<br />
            &nbsp;&nbsp;<input type="file" name="img3" size="30" />
						<?	
						}
						?>
           </td>
					 <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img4']) && file_exists("../imoveis/133x100/".$_POST['img4'])){
						?>
						Imagem 4<br />
            <input type="file" name="img4" size="30" /> <a href="javascript: remover_imagem_imovel(4, <?=$_POST['id_imovel']?>)" id="link_img_4">[remover imagem]</a>
						<div style="margin-top: 10px" id="img4"><img src="../imoveis/133x100/<?=$_POST['img4']?>" /></div>
						<?
						} else {
						?>
						Imagem 4<br />
            <input type="file" name="img4" size="30" />
						<?	
						}
						?>
           </td>
				</tr>
				
				<tr>
           <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img5']) && file_exists("../imoveis/133x100/".$_POST['img5'])){
						?>
						&nbsp;&nbsp;Imagem 5<br />
            &nbsp;&nbsp;<input type="file" name="img5" size="30" /> <a href="javascript: remover_imagem_imovel(5, <?=$_POST['id_imovel']?>)" id="link_img_5">[remover imagem]</a>
						<div style="margin: 10px" id="img5"><img src="../imoveis/133x100/<?=$_POST['img5']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 5<br />
            &nbsp;&nbsp;<input type="file" name="img5" size="30" />
						<?	
						}
						?>
           </td>
					 <td class="bg_incluir" valign="top">
						<?php
						if (!empty($_POST['img6']) && file_exists("../imoveis/133x100/".$_POST['img6'])){
						?>
						&nbsp;&nbsp;Imagem 6<br />
            &nbsp;&nbsp;<input type="file" name="img6" size="30" /> <a href="javascript: remover_imagem_imovel(6, <?=$_POST['id_imovel']?>)" id="link_img_6">[remover imagem]</a>
						<div style="margin: 10px" id="img6"><img src="../imoveis/133x100/<?=$_POST['img6']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 6<br />
            &nbsp;&nbsp;<input type="file" name="img6" size="30" />
						<?	
						}
						?>
					 </td>
				</tr>
				
				<tr>
           <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img7']) && file_exists("../imoveis/133x100/".$_POST['img7'])){
						?>
						&nbsp;&nbsp;Imagem 7<br />
            &nbsp;&nbsp;<input type="file" name="img7" size="30" /> <a href="javascript: remover_imagem_imovel(7, <?=$_POST['id_imovel']?>)" id="link_img_7">[remover imagem]</a>
						<div style="margin: 10px" id="img7"><img src="../imoveis/133x100/<?=$_POST['img7']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 7<br />
            &nbsp;&nbsp;<input type="file" name="img7" size="30" />
						<?	
						}
						?>
           </td>
					 <td class="bg_incluir" valign="top">
						<?php
						if (!empty($_POST['img8']) && file_exists("../imoveis/133x100/".$_POST['img8'])){
						?>
						&nbsp;&nbsp;Imagem 8<br />
            &nbsp;&nbsp;<input type="file" name="img8" size="30" /> <a href="javascript: remover_imagem_imovel(8, <?=$_POST['id_imovel']?>)" id="link_img_8">[remover imagem]</a>
						<div style="margin: 10px" id="img8"><img src="../imoveis/133x100/<?=$_POST['img8']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 8<br />
            &nbsp;&nbsp;<input type="file" name="img8" size="30" />
						<?	
						}
						?>
					 </td>
				</tr>
				
				<tr>
           <td class="bg_incluir" valign="top">
            <?php
						if (!empty($_POST['img9']) && file_exists("../imoveis/133x100/".$_POST['img9'])){
						?>
						&nbsp;&nbsp;Imagem 9<br />
            &nbsp;&nbsp;<input type="file" name="img9" size="30" /> <a href="javascript: remover_imagem_imovel(9, <?=$_POST['id_imovel']?>)" id="link_img_9">[remover imagem]</a>
						<div style="margin: 10px" id="img9"><img src="../imoveis/133x100/<?=$_POST['img9']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 9<br />
            &nbsp;&nbsp;<input type="file" name="img9" size="30" />
						<?	
						}
						?>
           </td>
					 <td class="bg_incluir" valign="top">
						<?php
						if (!empty($_POST['img10']) && file_exists("../imoveis/133x100/".$_POST['img10'])){
						?>
						&nbsp;&nbsp;Imagem 10<br />
            &nbsp;&nbsp;<input type="file" name="img10" size="30" /> <a href="javascript: remover_imagem_imovel(10, <?=$_POST['id_imovel']?>)" id="link_img_10">[remover imagem]</a>
						<div style="margin: 10px" id="img10"><img src="../imoveis/133x100/<?=$_POST['img10']?>" /></div>
						<?
						} else {
						?>
						&nbsp;&nbsp;Imagem 10<br />
            &nbsp;&nbsp;<input type="file" name="img10" size="30" />
						<?	
						}
						?>
					 </td>
				</tr>
				
        <tr>
						<td class="bg_incluir">
            &nbsp;&nbsp;Observações<br />
            &nbsp;&nbsp;<input name="obs" type="text" id="obs" size="53" value="<?php echo @stripslashes($_POST['obs'])?>" />
           </td>
           <td class="bg_incluir">
            Ativo *<br />
            <?=input_select('ativo', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>    
           </td>
        </tr>
				
        </tr>
        <tr>
        <td class="grid_topo" height="30" align="center" colspan="2">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
      $(document).ready(function(){
        $("#valor_aluguel_venda").maskMoney({
          thousands: '.',
          decimal: ',',  
        });  
      });
		</script>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		if (!isset($_POST['registro'])){
			header("Location: index.php?blc=".$blc."&acao=list");
			exit;
		}
		
		if (!isset($_POST['registro'])){
      $erro[] = 'Não foi possível excluir.';
    }
		
		// tudo ok ?
		if (!isset($erro)){
			$id_imovel = normaltxt($_POST['registro']);
			
			$sql_imovel = "SELECT * FROM imoveis WHERE id_imovel = '$id_imovel'";
			$exe_imovel = mysql_query($sql_imovel, $base) or aw_error(mysql_error());
			$num_imovel = mysql_num_rows($exe_imovel);
			if ($num_imovel > 0){
				$reg_imovel = mysql_fetch_array($exe_imovel, MYSQL_ASSOC);
				foreach($ar_tamanhos as $tam){
					if (!empty($reg_imovel['img1'])){
						if (file_exists("../imoveis/".$tam."/".$reg_imovel['img1'])){
							unlink("../imoveis/".$tam."/".$reg_imovel['img1']);
						}	
					}
					if (!empty($reg_imovel['img2'])){
						if (file_exists("../imoveis/".$tam."/".$reg_imovel['img2'])){
							unlink("../imoveis/".$tam."/".$reg_imovel['img2']);
						}	
					}
					if (!empty($reg_imovel['img3'])){
						if (file_exists("../imoveis/".$tam."/".$reg_imovel['img3'])){
							unlink("../imoveis/".$tam."/".$reg_imovel['img3']);
						}	
					}
					if (!empty($reg_imovel['img4'])){
						if (file_exists("../imoveis/".$tam."/".$reg_imovel['img4'])){
							unlink("../imoveis/".$tam."/".$reg_imovel['img4']);
						}	
					}
					if (!empty($reg_imovel['img5'])){
						if (file_exists("../imoveis/".$tam."/".$reg_imovel['img5'])){
							unlink("../imoveis/".$tam."/".$reg_imovel['img5']);
						}	
					}

				}
				
				$sql_delete = "DELETE FROM imoveis WHERE id_imovel = '$id_imovel'";
				$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
				
				header("Location: index.php?blc=".$blc."&acao=list&sucesso");
				exit;	
			} else {
				$erro[] = 'Imóvel não encontrado.';
			}
			
			
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td colspan="2" class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
		<tr>
		<td>
		<?php
		if (isset($erro) && count($erro) > 0){
			erro_bloco($erro);
		}
		?>
		</td>
		</tr>
		 <td class="grid_topo" height="30" align="center">
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
		</table>
		<?php
		
	}
?>
<script type="text/javascript">
	function remover_imagem_imovel(img, id_imovel){
		$.ajax({
			type: "GET",
			url: "/painel/inc/blc/ajax-remover-imagem.php",
			data: { img: img, id_imovel: id_imovel },
			cache : false
		}).done(function( retorno ) {
			r = jQuery.parseJSON(retorno);
			if (r.erro == ''){
				$("#img"+img).slideUp(350);
				$("#link_img_"+img).hide();
			} else {
				alert(r.erro);
			}
		});
	}
</script>
