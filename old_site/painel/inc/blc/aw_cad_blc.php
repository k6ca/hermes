<?php
	// Verificando se é include
	$arq_bloco = 'aw_cad_blc.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
		exit;
	}
		
	$coluna_busca = array(
				'aw_bloco.nome'=>'Nome',
				'aw_bloco.descricao'=>'Descri&ccedil;&atilde;o',
				'aw_bloco.bloco'=>'Bloco',
				'aw_modulo.nome_modulo'=>'M&oacute;dulo'
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
			$sql_total = "SELECT count(*) as total_pag FROM aw_bloco";
			$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo ORDER BY nome LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'aw_bloco.nome';
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
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT aw_bloco.*, aw_modulo.nome_modulo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = aw_modulo.id_modulo AND $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Bloco excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
			<td width="24%" class="top_mod_blc">&nbsp;NOME</td>
			<td width="38%" class="top_mod_blc">&nbsp;DESCRI&Ccedil;&Atilde;O</td>
			<td width="14%" class="top_mod_blc">&nbsp;BLOCO</td>
			<td width="20%" class="top_mod_blc">&nbsp;M&Oacute;DULO</td>
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
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_bloco'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['nome'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['descricao'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['bloco'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['nome_modulo'])?></td>
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
			<input type="button" value="Alterar" class="campo_grid" onClick="gen_submit('<?php echo $blc?>','alt','grid_grid')" <?=$btn_crud?> />
			<input type="button" value="Excluir" class="campo_grid" onClick="gen_submit('<?php echo $blc?>','del','grid_grid')" <?=$btn_crud?> /></td>
  		</tr>
		</table>
		</form>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Incluir')){
		// validando informações e cadastrando caso esteja correto.
		if (isset($_POST['form_submit'])){
			// validação começa aqui
			// validando nome_blc
			if (isset($_POST['nome_blc'])){
			    if (empty($_POST['nome_blc'])){
				$erro[] = 'Informe o nome do bloco.';
			    } else {
				if (strlen($_POST['nome_blc']) > 50){
				    $erro[] = 'Nome do bloco com no máximo 50 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe o nome do bloco.';
			}
			// validando descricao_blc
			if (isset($_POST['descricao_blc'])){
			    if (empty($_POST['descricao_blc'])){
				$erro[] = 'Informe uma descrição para esse bloco.';
			    } else {
				if (strlen($_POST['descricao_blc']) > 200){
				    $erro[] = 'A descrição do bloco deve conter no máximo 200 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe uma descrição para esse bloco.';
			}
			// validando blc_blc
			if (isset($_POST['blc_blc'])){
			    if (empty($_POST['blc_blc'])){
				$erro[] = 'Informe o nome do arquivo do bloco.';
			    } else {
				if (strlen($_POST['blc_blc']) > 20){
				    $erro[] = 'Nome do arquivo do bloco com no máximo 20 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe o nome do arquivo do bloco.';
			}
			// validando posicao_blc
			if (isset($_POST['posicao_blc'])){
			    if (empty($_POST['posicao_blc'])){
				$erro[] = 'Informe a posição deste bloco.';
			    } else {
				if (!is_numeric($_POST['posicao_blc'])){
				    $erro[] = 'A posição do bloco deve ser numérica.';
				}
			    }
			} else {
			    $erro[] = 'Informe a posição deste bloco.';
			}
			// validando ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo'])){
					$erro[] = 'Informe se o bloco está ativo ou não.';
				} else {
					if ($_POST['ativo'] != 'S' && $_POST['ativo'] != 'N'){
						$erro[] = "Informe se o bloco está ativo ou não.";
					}
				}
			} else {
				$erro[] = 'Informe se o bloco está ativo ou não.';
			}
            // validando modulo_blc
            if (isset($_POST['modulo_blc'])){
              if (empty($_POST['modulo_blc']) || !is_numeric($_POST['modulo_blc'])){
                $erro[] = 'Informe o módulo.';
              }
            } else {
              $erro[] = 'Informe o módulo.';
            }
			// validando iniciar
			if (isset($_POST['iniciar'])){
				if ($_POST['iniciar'] != 0 && $_POST['iniciar'] != 1){
					$erro[] = "Informe se o módulo irá começar maximizado ou minimizado.";
				}
			} else {
				$erro[] = "Informe se o módulo irá começar maximizado ou minimizado.";
			}
			// validando hab_btn
			if (isset($_POST['hab_btn'])){
				if ($_POST['hab_btn'] != 'S' && $_POST['hab_btn'] != 'N'){
					$erro[] = "Informe se o módulo irá ter o botão maximizar e minimizar habilitados.";
				}
			} else {
				$erro[] = "Informe se o módulo irá ter o botão maximizar e minimizar habilitados.";
			}
			// sistema
			if (isset($_POST['sistema'])){
				if (empty($_POST['sistema'])){
					$erro[] = 'Informe se este é um bloco do sistema.';
				} else {
					if (!in_array($_POST['sistema'], array('S','N'))){
							$erro[] = 'Informe se este é um bloco do sistema.';
					}
				}
			} else {
                $erro[] = 'Informe se este é um bloco do sistema.';
			}
			// oculto
			if (isset($_POST['oculto'])){
              if (empty($_POST['oculto'])){
                $erro[] = 'Informe se este bloco será oculto.';
              } else {
                if ($_POST['oculto'] != 'S' && $_POST['oculto'] != 'N'){
                  $erro[] = 'Informe se este bloco será oculto.';
                }
              }
			} else {
              $erro[] = 'Informe se este bloco será oculto.';
            }
			
			// blc_blc
			if (!isset($erro)){
				$sql_ver_blc = "SELECT count(nome) as tem_blc FROM aw_bloco WHERE bloco = '".normaltxt($_POST['blc_blc'])."'";
				$exe_ver_blc = mysql_query($sql_ver_blc, $base) or aw_error(mysql_error());
				$reg_ver_blc = mysql_fetch_array($exe_ver_blc, MYSQL_ASSOC);
				if ($reg_ver_blc['tem_blc'] > 0 ){
					$erro[] = 'Um bloco com esse nome já existe.';
				}
			}
            // layout
            if (isset($_POST['layout'])){
              if (empty($_POST['layout'])){
                $erro[] = 'Informe qual tipo de layout.';
              } else {
                if ($_POST['layout'] != 'S' && $_POST['layout'] != 'N'){
                  $erro[] = 'Informe qual tipo de layout.';
                }
              }
			} else {
              $erro[] = 'Informe qual tipo de layout.';
            }

			
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$id_modulo = normaltxt($_POST['modulo_blc']);
				$nome	   = normaltxt($_POST['nome_blc'],false,true);
				$descricao = normaltxt($_POST['descricao_blc'],false,true);
				$bloco	   = normaltxt($_POST['blc_blc']);
				$data	   = date("Y-m-d H:i:s");
				$posicao   = (strlen($_POST['posicao_blc']) < 1) ? '0' : normaltxt($_POST['posicao_blc']);
				$link	   = normaltxt($_POST['link_blc']);
				$ativo	   = normaltxt($_POST['ativo']);
				$iniciar   = normaltxt($_POST['iniciar']);
				$hab_btn   = normaltxt($_POST['hab_btn']);
				$sistema   = normaltxt($_POST['sistema']);
                $oculto    = normaltxt($_POST['oculto']);
                $layout    = normaltxt($_POST['layout']);
				
				$sql_insert = "INSERT INTO `aw_bloco` (`id_modulo` , `nome` , `descricao` , `bloco` , `data_cadastro` , `posicao` , `link` , `ativo`, `iniciar`, `hab_btn`, `sistema`, `oculto`, `layout`)
							   VALUES (
							   '$id_modulo', '$nome', '$descricao', '$bloco', '$data', '$posicao', '$link', '$ativo', '$iniciar', '$hab_btn', '$sistema', '$oculto', '$layout'
							   )";
				$exe_insert = mysql_query($sql_insert, $base) or aw_error(mysql_error());
				
				// pegando informações de permissões do modulo 
				// para esse bloco pra adicionar permissões do bloco
				$sql_list_mod = "SELECT perm_modulo FROM aw_modulo WHERE id_modulo = '$id_modulo'";
				$exe_list_mod = mysql_query($sql_list_mod, $base) or aw_error(mysql_error());
				$reg_list_mod = mysql_fetch_array($exe_list_mod, MYSQL_ASSOC);
				if (strlen($reg_list_mod['perm_modulo']) > 0){
					//$mod_perm = $reg_list_mod['perm_modulo'].",".$bloco;
					$mod_perm_se   = unserialize($reg_list_mod['perm_modulo']);
					if (!in_array($bloco,$mod_perm_se)){
						$mod_perm_se[] = $bloco;
					}
				} else {
					//$mod_perm = $bloco;
					$mod_perm_se[] = $bloco;
				}
				$mod_perm = serialize($mod_perm_se);
				// fazendo o updade
				$sql_up_mod = "UPDATE aw_modulo SET perm_modulo = '$mod_perm' WHERE id_modulo = '$id_modulo'";
				$exe_up_mod = mysql_query($sql_up_mod,$base) or aw_error(mysql_error());
				
                log_sistema("Cadastrou um novo bloco (Bloco: ".$nome.")");
                
				unset($_POST);
                
                $sucesso = true;
			}
		}
		?>
		<form name="frm_incluir" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Incluir" method="post" onSubmit="return performCheck('frm_incluir', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td colspan="3" class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td colspan="3" class="bg_incluir">
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
          <td colspan="3" class="bg_incluir">
            <ul id="sucesso_sistema">
                <li>Bloco cadastrado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
        <tr>
        <td colspan="3" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
        <tr>
    	<td width="34%" class="bg_incluir">&nbsp;&nbsp;Nome *<br />
		&nbsp;&nbsp;<input name="nome_blc" type="text" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['nome_blc'])?>" /></td>
    	<td width="33%" class="bg_incluir">Descri&ccedil;&atilde;o *<br />
		<input name="descricao_blc" type="text" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['descricao_blc'])?>" />    	</td>
    	<td width="33%" class="bg_incluir">Bloco *<br />
		<input name="blc_blc" type="text" class="campo_form" size="30" maxlength="20" value="<?php echo @stripslashes($_POST['blc_blc'])?>" /></td>
  		</tr>
  		<tr>
  		  <td class="bg_incluir">&nbsp;&nbsp;Posi&ccedil;&atilde;o *<br />
&nbsp;&nbsp;<input name="posicao_blc" type="text" class="campo_form" value="<?php echo @stripslashes($_POST['posicao_blc'])?>" size="6" maxlength="2" /></td>
  		  <td class="bg_incluir">Link<br />
	      <input name="link_blc" type="text" class="campo_form" value="<?php echo @stripslashes($_POST['link_blc'])?>" size="30" /></td>
  		  <td class="bg_incluir">Ativo *<br />
            <select name="ativo" id="ativo">
              <?php
		if (isset($_POST['ativo']) && $_POST['ativo'] == 'S'){
	 	 	$ativo_s = 'selected="selected"';
			$ativo_n = '';
		  } elseif (isset($_POST['ativo']) && $_POST['ativo'] == 'N'){
		  	$ativo_s = '';
			$ativo_n = 'selected="selected"';
	 	 } else {
	 	 	$ativo_s = 'selected="selected"';
			$ativo_n = '';
	  	}
	  	?>
              <option value="S" <?php echo $ativo_s?>>Sim</option>
              <option value="N" <?php echo $ativo_n?>>N&atilde;o</option>
            </select></td>
		  </tr>
  		<tr>
    	<td class="bg_incluir">&nbsp;&nbsp;Módulo *<br />
    	  &nbsp;&nbsp;<select name="modulo_blc" class="campo_form">
		  <?php
		  	$sql_list_mod = "SELECT id_modulo, nome_modulo FROM aw_modulo ORDER BY nome_modulo ASC";
			$exe_list_mod = mysql_query($sql_list_mod, $base) or aw_error(mysql_error());
			while ($reg_list_mod = mysql_fetch_array($exe_list_mod, MYSQL_ASSOC)){
				if ($reg_list_mod['id_modulo'] == (@$_POST['modulo_blc'])){
			?>
			<option value="<?php echo $reg_list_mod['id_modulo']?>" selected="selected"><?php echo $reg_list_mod['nome_modulo']?></option>
			<?php
				} else {
			?>
			<option value="<?php echo $reg_list_mod['id_modulo']?>"><?php echo $reg_list_mod['nome_modulo']?></option>
			<?php
				}
			}
		  ?>
    	  </select></td>
    	<td class="bg_incluir">Iniciar *<br />
		<select name="iniciar" class="campo_form">
		  <?php
		  if (isset($_POST['iniciar'])){
		  	if ($_POST['iniciar'] == 0){
				$max = '';
				$min = ' selected="selected"';
			} else {
				$max = ' selected="selected"';
				$min = '';
			}
		  } else {
		  	$max = '';
			$min = ' selected="selected"';
		  }
		  ?>
		  <option value="0"<?php echo $min?>>Minimizado</option>
		  <option value="1"<?php echo $max?>>Maximizado</option>
				</select></td>
    	<td class="bg_incluir">
		Maximizar / Minimizar Habilitado *<br />
            <select name="hab_btn" class="campo_form">
			<?php
			  if (isset($_POST['hab_btn'])){
				if ($_POST['hab_btn'] == 'S'){
					$btn_1 = ' selected="selected"';
					$btn_2 = '';
				} else {
					$btn_1 = '';
					$btn_2 = ' selected="selected"';
				}
			  } else {
				$btn_1 = ' selected="selected"';
				$btn_2 = '';
			  }
			  ?>
              <option value="S"<?php echo $btn_1?>>Sim</option>
              <option value="N"<?php echo $btn_2?>>Não</option>
            </select>
		</td>
  		</tr>
	<tr>
    	<td class="bg_incluir">&nbsp;&nbsp;Bloco do sistema *<br />
    	  &nbsp;&nbsp;<select name="sistema" class="campo_form">
		  <?php
		  	$ar_sistema = array('N'=>'Não', 'S'=>'Sim');
			foreach($ar_sistema as $c => $v){
				if (@$_POST['sistema'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select></td>
    	<td class="bg_incluir">
		Bloco oculto *<br />
		<select name="oculto" class="campo_form">
		  <?php
		  	$ar_oculto = array('N'=>'Não', 'S'=>'Sim');
			foreach($ar_oculto as $c => $v){
				if (@$_POST['oculto'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select>
	</td>
    	<td class="bg_incluir">
          Tipo de layout *<br />
          <select name="layout" class="campo_form">
		  <?php
		  	$ar_layout = array('S'=>'Layout Padrão', 'N'=>'Layout Personalizado');
			foreach($ar_layout as $c => $v){
				if (@$_POST['layout'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select>
        </td>
  		</tr>
       	<tr>
          <td width="34%" class="bg_incluir_p">&nbsp;</td>
          <td width="33%" class="bg_incluir_p">&nbsp;</td>
		  <td width="33%" class="bg_incluir_p">&nbsp;</td>
       </tr>
        <tr>
        <td colspan="3" class="grid_topo" height="30" align="center">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
			rules[0]  = 'nome_blc|required|Coloque um nome.';
			rules[1]  = 'descricao_blc|required|Coloque uma Descrição.';
            rules[2]  = 'posicao_blc|required|Informe a posição do bloco.';
            rules[3]  = 'posicao_blc|integer|O posição deve ser numérica.';
			rules[4]  = 'blc_blc|required|Informe o código do bloco.';
			rules[5]  = 'ativo|required|Informe se esse bloco está ativo ou não.';
			rules[6]  = 'modulo_blc|required|Informe o módulo que este bloco pertence.';
			rules[7]  = 'iniciar|required|Informe se o módulo irá começar maximizado ou minimizado.';
			rules[8]  = 'hab_btn|required|Informe se os botões maximizar e minimizar estarão habilitados.';
			rules[9]  = 'sistema|required|Informe se este é um bloco do sistema.';
			rules[10] = 'oculto|required|Informe se este bloco será oculto.';
            rules[11] = 'layout|required|Informe qual tipo de layout.';
		</script>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			// verificando se aquele id existe
			$sql_ver_user = "SELECT count(nome) as tem_bloco FROM aw_bloco WHERE id_bloco = '".normaltxt($_POST['registro'])."'";
			$exe_ver_user = mysql_query($sql_ver_user, $base) or aw_error(mysql_error());
			$reg_ver_user = mysql_fetch_array($exe_ver_user, MYSQL_ASSOC);
			if ($reg_ver_user['tem_bloco'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list&lllll");
				exit;

			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM aw_bloco WHERE id_bloco = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
			
			$_POST['nome_blc']	    = stripslashes($reg_select['nome']);
			$_POST['descricao_blc'] = stripslashes($reg_select['descricao']);
			$_POST['blc_blc']	    = stripslashes($reg_select['bloco']);
			$_POST['posicao_blc']	= stripslashes($reg_select['posicao']);
			$_POST['link_blc']	    = stripslashes($reg_select['link']);
			$_POST['modulo_blc']	= stripslashes($reg_select['id_modulo']);
			$_POST['ativo']		    = stripslashes($reg_select['ativo']);
			$_POST['id_bloco']	    = $_POST['registro'];
			$_POST['iniciar']	    = stripslashes($reg_select['iniciar']);
			$_POST['hab_btn']	    = stripslashes($reg_select['hab_btn']);
			$_POST['last_mod']	    = stripslashes($reg_select['id_modulo']);
			$_POST['sistema']	    = stripslashes($reg_select['sistema']);
            $_POST['oculto']        = stripslashes($reg_select['oculto']);
            $_POST['layout']        = stripslashes($reg_select['layout']);
			
		} else {
			// validando id_bloco
			if (isset($_POST['id_bloco'])){
				if (empty($_POST['id_bloco']) || !is_numeric($_POST['id_bloco'])){
					$erro[] = 'Erro ao alterar módulo.';
				}
			} else {
				$erro[] = 'Erro ao alterar módulo.';
			}
			// validando nome_blc
			if (isset($_POST['nome_blc'])){
			    if (empty($_POST['nome_blc'])){
				$erro[] = 'Informe o nome do bloco.';
			    } else {
				if (strlen($_POST['nome_blc']) > 50){
				    $erro[] = 'Nome do bloco com no máximo 50 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe o nome do bloco.';
			}
			// validando descricao_blc
			if (isset($_POST['descricao_blc'])){
			    if (empty($_POST['descricao_blc'])){
				$erro[] = 'Informe uma descrição para esse bloco.';
			    } else {
				if (strlen($_POST['descricao_blc']) > 200){
				    $erro[] = 'A descrição do bloco deve conter no máximo 200 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe uma descrição para esse bloco.';
			}
			// validando blc_blc
			if (isset($_POST['blc_blc'])){
			    if (empty($_POST['blc_blc'])){
				$erro[] = 'Informe o nome do arquivo do bloco.';
			    } else {
				if (strlen($_POST['blc_blc']) > 20){
				    $erro[] = 'Nome do arquivo do bloco com no máximo 20 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe o nome do arquivo do bloco.';
			}
			// validando posicao_blc
			if (isset($_POST['posicao_blc'])){
			    if (empty($_POST['posicao_blc'])){
				$erro[] = 'Informe a posição deste bloco.';
			    } else {
				if (!is_numeric($_POST['posicao_blc'])){
				    $erro[] = 'A posição do bloco deve ser numérica.';
				}
			    }
			} else {
			    $erro[] = 'Informe a posição deste bloco.';
			}
			// validando ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo'])){
					$erro[] = 'Informe se o bloco está ativo ou não.';
				} else {
					if ($_POST['ativo'] != 'S' && $_POST['ativo'] != 'N'){
						$erro[] = "Informe se o bloco está ativo ou não.";
					}
				}
			} else {
				$erro[] = 'Informe se o bloco está ativo ou não.';
			}
            // validando modulo_blc
            if (isset($_POST['modulo_blc'])){
              if (empty($_POST['modulo_blc']) || !is_numeric($_POST['modulo_blc'])){
                $erro[] = 'Informe o módulo.';
              }
            } else {
              $erro[] = 'Informe o módulo.';
            }
			if (!isset($_POST['last_mod'])){
				$erro[] = "Houve um erro no banco de dados, tente novamente.";
			}
			// validando iniciar
			if (isset($_POST['iniciar'])){
				if ($_POST['iniciar'] != 0 && $_POST['iniciar'] != 1){
					$erro[] = "Informe se o módulo irá começar maximizado ou minimizado.";
				}
			} else {
				$erro[] = "Informe se o módulo irá começar maximizado ou minimizado.";
			}
			// validando hab_btn
			if (isset($_POST['hab_btn'])){
				if ($_POST['hab_btn'] != 'S' && $_POST['hab_btn'] != 'N'){
					$erro[] = "Informe se o módulo irá ter o botão maximizar e minimizar habilitados.";
				}
			} else {
				$erro[] = "Informe se o módulo irá ter o botão maximizar e minimizar habilitados.";
			}
            // oculto
			if (isset($_POST['oculto'])){
              if (empty($_POST['oculto'])){
                $erro[] = 'Informe se este bloco será oculto.';
              } else {
                if ($_POST['oculto'] != 'S' && $_POST['oculto'] != 'N'){
                  $erro[] = 'Informe se este bloco será oculto.';
                }
              }
			} else {
              $erro[] = 'Informe se este bloco será oculto.';
            }
            // layout
            if (isset($_POST['layout'])){
              if (empty($_POST['layout'])){
                $erro[] = 'Informe qual tipo de layout.';
              } else {
                if ($_POST['layout'] != 'S' && $_POST['layout'] != 'N'){
                  $erro[] = 'Informe qual tipo de layout.';
                }
              }
			} else {
              $erro[] = 'Informe qual tipo de layout.';
            }
			
			
			// tudo ok?
			if (!isset($erro)){
				$id_modulo	= normaltxt($_POST['modulo_blc']);
				$nome		= normaltxt($_POST['nome_blc'],false,true);
				$descricao	= normaltxt($_POST['descricao_blc'],false,true);
				$posicao	= normaltxt($_POST['posicao_blc']);
				$link		= normaltxt($_POST['link_blc']);
				$ativo		= normaltxt($_POST['ativo']);
				$id_bloco	= normaltxt($_POST['id_bloco']);
				$bloco		= normaltxt($_POST['blc_blc']);
				$iniciar	= normaltxt($_POST['iniciar']);
				$hab_btn	= normaltxt($_POST['hab_btn']);
				$sistema	= normaltxt($_POST['sistema']);
                $oculto     = normaltxt($_POST['oculto']);
                $layout     = normaltxt($_POST['layout']);
				
				
				$sql_update = "UPDATE aw_bloco SET 
							   nome      = '$nome',
							   id_modulo = '$id_modulo',
							   descricao = '$descricao',
							   posicao   = '$posicao',
							   link      = '$link',
							   ativo     = '$ativo',
							   iniciar   = '$iniciar',
							   hab_btn   = '$hab_btn',
							   sistema   = '$sistema',
                               oculto    = '$oculto',
                               layout    = '$layout' 
							   WHERE
							   id_bloco  = '$id_bloco'
							   ";
				
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
				
				// retirnando permissão do bloco antigo e colocando no novo
				$sql_list_mod = "SELECT perm_modulo FROM aw_modulo WHERE id_modulo = '".normaltxt($_POST['last_mod'])."'";
				$exe_list_mod = mysql_query($sql_list_mod, $base) or aw_error(mysql_error());
				$reg_list_mod = mysql_fetch_array($exe_list_mod, MYSQL_ASSOC);
				$perm_array   = unserialize($reg_list_mod['perm_modulo']);
				
				for ($i2=0;$i2<count($perm_array);$i2++){
					if (isset($perm_array[$i2])){
						if ($perm_array[$i2] == $bloco){
							unset($perm_array[$i2]);
						}	
					}
				}
				
				$perm_array2 = serialize($perm_array);
				$sql_up_mod  = "UPDATE aw_modulo SET perm_modulo = '$perm_array2' WHERE id_modulo = '".normaltxt($_POST['last_mod'])."'";
				$exe_up_mod  = mysql_query($sql_up_mod,$base) or aw_error(mysql_error());
				
                // adicionando a nova permissão para o novo módulo
				$sql_list_md = "SELECT perm_modulo FROM aw_modulo WHERE id_modulo = '$id_modulo'";
				$exe_list_md = mysql_query($sql_list_md, $base) or aw_error(mysql_error());
				$reg_list_md = mysql_fetch_array($exe_list_md, MYSQL_ASSOC);
				$perm_array3 = unserialize($reg_list_md['perm_modulo']);
				if (!in_array($bloco,$perm_array3)){
					$perm_array3[] = $bloco;
					$perm_array4 = serialize($perm_array3);
					$sql_up_md = "UPDATE aw_modulo SET perm_modulo = '$perm_array4' WHERE id_modulo = '".normaltxt($id_modulo)."'";
					$exe_up_md = mysql_query($sql_up_md,$base) or aw_error(mysql_error());
				}
                
                log_sistema("Alterou um bloco (Bloco: ".$nome.")");
		
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onSubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_bloco" value="<?php echo @$_POST['id_bloco'] ?>" />
		<input type="hidden" name="last_mod" value="<?php echo @$_POST['last_mod'] ?>" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td colspan="3" class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td colspan="3" class="bg_incluir">
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
          <td colspan="3" class="bg_incluir">
            <ul id="sucesso_sistema">
                <li>Bloco alterado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
        <tr>
        <td colspan="3" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
        <tr>
    	<td width="34%" class="bg_incluir">&nbsp;&nbsp;Nome *<br />
		&nbsp;&nbsp;<input name="nome_blc" type="text" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['nome_blc'])?>" /></td>
    	<td width="33%" class="bg_incluir">Descri&ccedil;&atilde;o *<br />
		<input name="descricao_blc" type="text" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['descricao_blc'])?>" />    	</td>
    	<td width="33%" class="bg_incluir">Bloco *<br />
		<input name="blc_blc" type="text" size="30" maxlength="20" value="<?php echo @stripslashes($_POST['blc_blc'])?>" class="read_only" readonly="readonly" /></td>
  		</tr>
  		<tr>
  		  <td class="bg_incluir">&nbsp;&nbsp;Posi&ccedil;&atilde;o *<br />
&nbsp;&nbsp;<input name="posicao_blc" type="text" class="campo_form" value="<?php echo @stripslashes($_POST['posicao_blc'])?>" size="6" maxlength="2" /></td>
  		  <td class="bg_incluir">Link<br />
	      <input name="link_blc" type="text" class="campo_form" value="<?php echo @stripslashes($_POST['link_blc'])?>" size="30" /></td>
  		  <td class="bg_incluir">Ativo *<br />
            <select name="ativo" id="ativo">
              <?php
		if (isset($_POST['ativo']) && $_POST['ativo'] == 'S'){
	 	 	$ativo_s = 'selected="selected"';
			$ativo_n = '';
		  } elseif (isset($_POST['ativo']) && $_POST['ativo'] == 'N'){
		  	$ativo_s = '';
			$ativo_n = 'selected="selected"';
	 	 } else {
	 	 	$ativo_s = 'selected="selected"';
			$ativo_n = '';
	  	}
	  	?>
              <option value="S" <?php echo $ativo_s?>>Sim</option>
              <option value="N" <?php echo $ativo_n?>>N&atilde;o</option>
            </select></td>
		  </tr>
  		<tr>
    	<td class="bg_incluir">&nbsp;&nbsp;Módulo *<br />
    	  &nbsp;&nbsp;<select name="modulo_blc" class="campo_form">
		  <?php
		  	$sql_list_mod = "SELECT id_modulo, nome_modulo FROM aw_modulo ORDER BY nome_modulo ASC";
			$exe_list_mod = mysql_query($sql_list_mod, $base) or aw_error(mysql_error());
			while ($reg_list_mod = mysql_fetch_array($exe_list_mod, MYSQL_ASSOC)){
				if ($reg_list_mod['id_modulo'] == (@$_POST['modulo_blc'])){
			?>
			<option value="<?php echo $reg_list_mod['id_modulo']?>" selected="selected"><?php echo $reg_list_mod['nome_modulo']?></option>
			<?php
				} else {
			?>
			<option value="<?php echo $reg_list_mod['id_modulo']?>"><?php echo $reg_list_mod['nome_modulo']?></option>
			<?php
				}
			}
		  ?>
    	  </select></td>
    	<td class="bg_incluir">Iniciar *<br />
		<select name="iniciar" class="campo_form">
		  <?php
		  if (isset($_POST['iniciar'])){
		  	if ($_POST['iniciar'] == 0){
				$max = '';
				$min = ' selected="selected"';
			} else {
				$max = ' selected="selected"';
				$min = '';
			}
		  } else {
		  	$max = '';
			$min = ' selected="selected"';
		  }
		  ?>
		  <option value="0"<?php echo $min?>>Minimizado</option>
		  <option value="1"<?php echo $max?>>Maximizado</option>
				</select></td>
    	<td class="bg_incluir">Maximizar / Minimizar Habilitado *<br />
            <select name="hab_btn" class="campo_form">
			<?php
			  if (isset($_POST['hab_btn'])){
				if ($_POST['hab_btn'] == 'S'){
					$btn_1 = ' selected="selected"';
					$btn_2 = '';
				} else {
					$btn_1 = '';
					$btn_2 = ' selected="selected"';
				}
			  } else {
				$btn_1 = ' selected="selected"';
				$btn_2 = '';
			  }
			  ?>
              <option value="S"<?php echo $btn_1?>>Sim</option>
              <option value="N"<?php echo $btn_2?>>Não</option>
            </select></td>
  		</tr>
	<tr>
    	<td class="bg_incluir">&nbsp;&nbsp;Bloco do sistema *<br />
    	  &nbsp;&nbsp;<select name="sistema" class="campo_form">
		  <?php
		  	$ar_sistema = array('N'=>'Não', 'S'=>'Sim');
			foreach($ar_sistema as $c => $v){
				if (@$_POST['sistema'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select></td>
    	<td class="bg_incluir">
          Bloco oculto *<br />
          <select name="oculto" class="campo_form">
		  <?php
		  	$ar_oculto = array('N'=>'Não', 'S'=>'Sim');
			foreach($ar_oculto as $c => $v){
				if (@$_POST['oculto'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select>
        </td>
    	<td class="bg_incluir">
          Tipo de layout *<br />
          <select name="layout" class="campo_form">
		  <?php
		  	$ar_layout = array('S'=>'Layout Padrão', 'N'=>'Layout Personalizado');
			foreach($ar_layout as $c => $v){
				if (@$_POST['layout'] == $c){
				?>
				<option value="<?=$c?>" selected="selected"><?=$v?>&nbsp&nbsp</option>
				<?
				} else {
				?>
				<option value="<?=$c?>"><?=$v?>&nbsp&nbsp</option>
				<?	
				}
			}
		  ?>
    	  </select>
        </td>
  		</tr>
       	<tr>
          <td width="34%" class="bg_incluir_p">&nbsp;</td>
          <td width="33%" class="bg_incluir_p">&nbsp;</td>
		  <td width="33%" class="bg_incluir_p">&nbsp;</td>
       </tr>
        <tr>
        <td colspan="3" class="bg_incluir_p">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="3" class="grid_topo" height="30" align="center">
		<input type="submit" value=" ALTERAR " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onClick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
			rules[0]  = 'nome_blc|required|Coloque um nome.';
			rules[1]  = 'descricao_blc|required|Coloque uma Descrição.';
            rules[2]  = 'posicao_blc|required|Informe a posição do bloco.';
            rules[3]  = 'posicao_blc|integer|O posição deve ser numérica.';
			rules[4]  = 'blc_blc|required|Informe o código do bloco.';
			rules[5]  = 'ativo|required|Informe se esse bloco está ativo ou não.';
			rules[6]  = 'modulo_blc|required|Informe o módulo que este bloco pertence.';
			rules[7]  = 'iniciar|required|Informe se o módulo irá começar maximizado ou minimizado.';
			rules[8]  = 'hab_btn|required|Informe se os botões maximizar e minimizar estarão habilitados.';
			rules[9]  = 'sistema|required|Informe se este é um bloco do sistema.';
            rules[10] = 'oculto|required|Informe se este bloco será oculto.';
            rules[11] = 'layout|required|Informe qual tipo de layout.';
		</script>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		if (!isset($_POST['registro'])){
			header("Location: index.php?blc=".$blc."&acao=list");
			exit;
		}
		if (!isset($_POST['registro']) || !is_numeric($_POST['registro'])){
			$erro[] = "Escolha um m&oacute;dulo do sistema para apagar.";
		}
		
		if ($_POST['registro'] == 1 || $_POST['registro'] == 2 || $_POST['registro'] == 3 || $_POST['registro'] == 4) {
			$erro[] = "Esse BLOCO não pode ser deletado por se tratar de um bloco indispensável para o funcionamento do sistema.";
            log_sistema("Tentou excluir um bloco que não pode ser excluído.");
        }
		
		// tudo ok ?
		if (!isset($erro)){
			$id_user = normaltxt($_POST['registro']);
			// seleciona o bloco do id que foi deletado
			$sql_list_blc = "SELECT id_modulo, bloco FROM aw_bloco WHERE id_bloco = '".normaltxt($_POST['registro'])."'";
			$exe_list_blc = mysql_query($sql_list_blc, $base) or aw_error(mysql_error());
			$num_list_blc = mysql_num_rows($exe_list_blc);
			if ($num_list_blc > 0){
				$reg_list_blc = mysql_fetch_array($exe_list_blc, MYSQL_ASSOC);
				$blc_user  = $reg_list_blc['bloco'];
				$id_modulo = $reg_list_blc['id_modulo'];
				// retirando permissões desse bloco dos usuários que tinham permissão para o mesmo
				$sql_del_perm_user = "DELETE FROM permissoes WHERE bloco = '$blc_user'";
				$exe_del_perm_user = mysql_query($sql_del_perm_user,$base) or aw_error(mysql_error());
				
				
				// retirando permissão que está sendo deletada da tabela aw_modulo
				$sql_list_mod = "SELECT perm_modulo FROM aw_modulo WHERE id_modulo = '$id_modulo'";
				$exe_list_mod = mysql_query($sql_list_mod, $base) or aw_error("Erro ao tentar selecionar permissão do módulos : ".mysql_error());
				$num_list_mod = mysql_num_rows($exe_list_mod);
				if ($num_list_mod > 0){
					$reg_list_mod = mysql_fetch_array($exe_list_mod, MYSQL_ASSOC);
					$acesso_do_mod = unserialize($reg_list_mod['perm_modulo']);
					for($i=0;$i<count($acesso_do_mod);$i++){
						if ($acesso_do_mod[$i] == $blc_user){
							unset($acesso_do_mod[$i]);
						}
					}
					$acesso_do_mod_pos = serialize($acesso_do_mod);
					// fazendo update das permissões dos usuários
					$sql_up_perm_mod = "UPDATE aw_modulo SET perm_modulo = '$acesso_do_mod_pos' WHERE id_modulo = '$id_modulo'";
					$exe_up_perm_mod = mysql_query($sql_up_perm_mod,$base) or aw_error(mysql_error());
					unset($acesso_do_mod,$acesso_do_mod_pos);
				}
			}			
			
			// deleta o bloco
			$sql_delete = "DELETE FROM aw_bloco WHERE id_bloco = '".normaltxt($_POST['registro'])."'";
			$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
			
            log_sistema("Deletou um bloco (Bloco Id: ".$id_user.")");
            
            header("Location: index.php?blc=".$blc."&acao=list&sucesso");
			exit;
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
		<input type="button" value=" FECHAR " class="campo_grid" onClick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
		</table>
		<?php
		
	}
	
?>
