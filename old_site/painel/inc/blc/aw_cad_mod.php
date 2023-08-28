<?php	
	// Verificando se é include
	$arq_bloco = 'aw_cad_mod.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){	
		exit;
	}
	$coluna_busca = array(
				'nome_modulo'=>'Nome',
				'descricao_modulo'=>'Descri&ccedil;&atilde;o'
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
			$sql_total = "SELECT count(*) as total_pag FROM aw_modulo";
			$sql_lista = "SELECT * FROM aw_modulo ORDER BY nome_modulo LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'nome_modulo';
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
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM aw_modulo WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_modulo WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Módulo excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
    		<td width="32%" class="top_mod_blc">&nbsp;NOME</td>
    		<td width="32%" class="top_mod_blc">&nbsp;DESCRI&Ccedil;&Atilde;O</td>
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
			<td width="4%" class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_modulo'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
    		<td width="20%" class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['nome_modulo'])?></td>
    		<td width="76%" class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['descricao_modulo'])?></td>
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
			// txt_nome
			if (isset($_POST['txt_nome'])){
				if (empty($_POST['txt_nome'])){
					$erro[] = "Informe o nome desse m&oacute;dulo.";
				} else {
					if (strlen($_POST['txt_nome']) > 50){
						$erro[] = 'O nome do módulo deve conter no máximo 50 caracteres.';
					}
				}
			} else {
				$erro[] = "Informe o nome desse m&oacute;dulo.";
			}
			// txt_descricao
			if (isset($_POST['txt_descricao'])){
				if (empty($_POST['txt_descricao'])){
					$erro[] = "Informe a descri&ccedil;&atilde;o desse m&oacute;dulo.";
				}
			} else {
				$erro[] = "Informe a descri&ccedil;&atilde;o desse m&oacute;dulo.";
			}
			// ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo'])){
					$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
				} else {
					if ($_POST['ativo'] != 'S' && $_POST['ativo'] != 'N'){
						$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
					}
				}
			} else {
				$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
			}
			// posicao
			if (isset($_POST['posicao'])){
				if (empty($_POST['posicao'])){
				  $erro[] = 'Informe a posição.';
				} else {
				  if (!is_numeric($_POST['posicao'])){
					$erro[] = 'Informe a posição.';
				  }
				}
			} else {
				$erro[] = 'Informe a posição.';
			}
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$txt_nome      = normaltxt($_POST['txt_nome']);
				$txt_descricao = normaltxt($_POST['txt_descricao']);
				$perm	       = "a:0:{}";
				$data	       = date("Y-m-d H:i:s");
				$ativo	       = normaltxt($_POST['ativo']);
				$posicao	   = normaltxt($_POST['posicao']);
				
				
				$sql_insert = "INSERT INTO aw_modulo (nome_modulo, descricao_modulo, perm_modulo, data_insc, ativo, posicao) VALUES 
							   ('$txt_nome', '$txt_descricao', '$perm', '$data', '$ativo', '$posicao')";
				$exe_insert = mysql_query($sql_insert, $base) or aw_error(mysql_error());
				
				unset($_POST);
				
				log_sistema("Cadastrou um novo módulo (Módulo: ".$txt_nome.")");
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_incluir" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Incluir" method="post" onsubmit="return performCheck('frm_incluir', rules, 'classic');">
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
                <li>Módulo cadastrado com sucesso.</li>
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
          &nbsp;&nbsp;<input name="txt_nome" type="text" id="txt_nome" size="30" maxlength="20" value="<?php echo @$_POST['txt_nome']?>" /></td>
           <td width="33%" class="bg_incluir">Descri&ccedil;&atilde;o *<br />
           <input name="txt_descricao" type="text" id="txt_descricao" size="30" maxlength="100" value="<?php echo @$_POST['txt_descricao']?>" />		   </td>
		   <td width="33%" class="bg_incluir">Ativo *<br />
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
          </select>          </td>
       </tr>
	   
	   <tr>
           <td width="34%" class="bg_incluir">&nbsp;&nbsp;Posição<br />
          &nbsp;&nbsp;<input name="posicao" type="text" id="posicao" size="10" maxlength="11" value="<?php echo @$_POST['posicao']?>" /></td>
           <td width="33%" class="bg_incluir"></td>
		   <td width="33%" class="bg_incluir"></td>
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
			rules[0] = 'txt_nome|required|Coloque um nome.';
			rules[1] = 'txt_descricao|required|Coloque uma Descrição.';
			rules[2] = 'ativo|required|Informe se o módulo está ativo ou não.';
			rules[3] = 'posicao|required|Informe a posição.';
			rules[4] = 'posicao|integer|A posição deve ser numérica.';
		</script>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			// verificando se aquele id existe
			$sql_ver_user = "SELECT count(nome_modulo) as tem_modulo FROM aw_modulo WHERE id_modulo = '".normaltxt($_POST['registro'])."'";
			$exe_ver_user = mysql_query($sql_ver_user, $base) or aw_error(mysql_error());
			$reg_ver_user = mysql_fetch_array($exe_ver_user, MYSQL_ASSOC);
			if ($reg_ver_user['tem_modulo'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;

			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM aw_modulo WHERE id_modulo = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
			
			$_POST['txt_nome'] 		= stripslashes($reg_select['nome_modulo']);
			$_POST['txt_descricao'] = stripslashes($reg_select['descricao_modulo']);
			$_POST['ativo'] 		= stripslashes($reg_select['ativo']);
			$_POST['posicao'] 		= stripslashes($reg_select['posicao']);
			$_POST['id_modulo'] 	= $_POST['registro'];
		
		} else {
			// validando
			// id_modulo
			if (isset($_POST['id_modulo'])){
				if (empty($_POST['id_modulo']) || !is_numeric($_POST['id_modulo'])){
					$erro[] = 'Erro ao alterar módulo.';
				}
			} else {
				$erro[] = 'Erro ao alterar módulo.';
			}
			// txt_nome
			if (isset($_POST['txt_nome'])){
				if (empty($_POST['txt_nome'])){
					$erro[] = "Informe o nome desse m&oacute;dulo.";
				} else {
					if (strlen($_POST['txt_nome']) > 50){
						$erro[] = 'O nome do módulo deve conter no máximo 50 caracteres.';
					}
				}
			} else {
				$erro[] = "Informe o nome desse m&oacute;dulo.";
			}
			// txt_descricao
			if (isset($_POST['txt_descricao'])){
				if (empty($_POST['txt_descricao'])){
					$erro[] = "Informe a descri&ccedil;&atilde;o desse m&oacute;dulo.";
				}
			} else {
				$erro[] = "Informe a descri&ccedil;&atilde;o desse m&oacute;dulo.";
			}
			// ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo'])){
					$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
				} else {
					if ($_POST['ativo'] != 'S' && $_POST['ativo'] != 'N'){
						$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
					}
				}
			} else {
				$erro[] = "Informe se o m&oacute;dulo está ativo ou não.";
			}
			// posicao
			if (isset($_POST['posicao'])){
				if (empty($_POST['posicao'])){
				  $erro[] = 'Informe a posição.';
				} else {
				  if (!is_numeric($_POST['posicao'])){
					$erro[] = 'Informe a posição.';
				  }
				}
			} else {
				$erro[] = 'Informe a posição.';
			}
			
			
			// tudo ok?
			if (!isset($erro)){
				$txt_nome		= normaltxt($_POST['txt_nome']);
				$txt_descricao	= normaltxt($_POST['txt_descricao']);
				$ativo			= normaltxt($_POST['ativo']);
				$id_modulo		= normaltxt($_POST['id_modulo']);
				$posicao		= normaltxt($_POST['posicao']);
				
				
				$sql_update = "UPDATE aw_modulo SET 
						nome_modulo 	 = '$txt_nome',
						descricao_modulo = '$txt_descricao',
						ativo 			 = '$ativo',
						posicao			 = '$posicao' 
						WHERE
						id_modulo = '$id_modulo'
						";
				
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
				
				log_sistema("Alterou um módulo (Módulo: ".$txt_nome.")");
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_modulo" value="<?php echo @$_POST['id_modulo'] ?>" />
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
                <li>Módulo alterado com sucesso.</li>
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
           <td width="34%" class="bg_incluir">&nbsp;&nbsp;Nome *<br />&nbsp;&nbsp;<input name="txt_nome" type="text" id="txt_nome" size="30" maxlength="20" value="<?php echo @$_POST['txt_nome']?>" /></td>
           <td width="33%" class="bg_incluir">Descri&ccedil;&atilde;o *<br />
           <input name="txt_descricao" type="text" id="txt_descricao" size="30" maxlength="100" value="<?php echo @$_POST['txt_descricao']?>" />		   </td>
		   <td width="33%" class="bg_incluir">Ativo *<br />
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
          </select>          </td>
       </tr>
	   <tr>
           <td width="34%" class="bg_incluir">&nbsp;&nbsp;Posição<br />
          &nbsp;&nbsp;<input name="posicao" type="text" id="posicao" size="10" maxlength="11" value="<?php echo @$_POST['posicao']?>" /></td>
           <td width="33%" class="bg_incluir"></td>
		   <td width="33%" class="bg_incluir"></td>
       </tr>
        <tr>
        <td colspan="3" class="bg_incluir_p">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="3" class="grid_topo" height="30" align="center">
		<input type="submit" value=" ALTERAR " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
			rules[0] = 'txt_nome|required|Coloque um nome.';
			rules[1] = 'txt_descricao|required|Coloque uma Descrição.';
			rules[2] = 'ativo|required|Informe se o módulo está ativo ou não.';
			rules[3] = 'posicao|required|Informe a posição.';
			rules[4] = 'posicao|integer|A posição deve ser numérica.';
		</script>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		if (!isset($_POST['registro'])){
			header("Location: index.php?blc=".$blc."&acao=list");
			exit;
		}
		
		// verificando se tem algum bloco nesse módulo
		$sql_list_blc = "SELECT count(*) as tem_blc FROM aw_bloco WHERE id_modulo = '".normaltxt($_POST['registro'])."'";
		$exe_list_blc = mysql_query($sql_list_blc,$base) or aw_error(mysql_error());
		$reg_list_blc = mysql_fetch_array($exe_list_blc, MYSQL_ASSOC);
		if ($reg_list_blc['tem_blc'] > 0){
			$erro[] = "Esse módulo contém blocos. Antes de deleta-lo reestruture os blocos para outro módulo.";
		}
		if ($_POST['registro'] == 1){
			$erro[] = "O módulo INÍCIO não pode ser deletado.";
		}
		
		// tudo ok ?
		if (!isset($erro)){
			$id_user = normaltxt($_POST['registro']);
			$sql_delete = "DELETE FROM aw_modulo WHERE id_modulo = '".normaltxt($_POST['registro'])."'";
			$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
			
			log_sistema("Deletou um módulo.");
			
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
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
		</table>
		<?php
		
	}
	
?>
