<?php
	// Verificando se é include
	$arq_bloco = 'logs_sistema.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
		exit;
	}
		
	$coluna_busca = array(
				'usuario'   =>'Usuário',
				'msg'       =>'Log',
				'data_hora' =>'Data',
				'ip'        =>'IP'
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
			$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S'";
			$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' ORDER BY id_log DESC LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'usuario';
				$txt_grid_tipo  = 1;
				$txt_grid_txt   = '';
			} else {
                if ($_POST['grid_campo'] == 'data_hora'){
                  if (isDate($_POST['grid_txt']) == true){
                    $txt_grid_txt   = normaltxt(data_padrao($_POST['grid_txt']));
                  }
                } else {
                  $txt_grid_txt   = normaltxt($_POST['grid_txt']); 
                }
                
                $txt_grid_campo = normaltxt($_POST['grid_campo']);
				$txt_grid_tipo  = normaltxt($_POST['grid_tipo']);
                
			}
			
			// busca feita pelo usuário
			switch($_POST['grid_tipo']){
				case 1:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM logs_sistema WHERE ativo = 'S' AND $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
			<td width="6%" class="top_mod_blc">&nbsp;ID</td>
            <td width="15%" class="top_mod_blc">&nbsp;DATA / HORA</td>
            <td width="15%" class="top_mod_blc">&nbsp;IP</td>
			<td width="20%" class="top_mod_blc">&nbsp;USUÁRIO</td>
			<td width="40%" class="top_mod_blc">&nbsp;MENSAGEM</td>
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
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_log'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['id_log'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes(datetime2datetimebr($reg_lista['data_hora']))?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['ip'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['usuario'])?></td>
            <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['msg'])?></td>
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
            <input type="button" value="Imprimir" class="campo_grid" onclick="gen_submit('<?php echo $blc?>','alt','grid_grid')" <?=$btn_crud?> />
			<input type="button" value="Excluir" class="campo_grid" <?=$btn_crud?> onClick="gen_submit('<?php echo $blc?>','del','grid_grid')" />
          </td>
  		</tr>
		</table>
		</form>
		<?php
		
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Incluir')){
      // não tem
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
	  if (isset($_POST['form_submit'])){
			// validando data_inicial
            if (isset($_POST['data_inicial'])){
                if (empty($_POST['data_inicial'])){
                    $erro[] = 'Informe a data inicial';
                } else {
                    if (isDate($_POST['data_inicial']) == false){
                        $erro[] = 'A data inicial informada é inválida.';
                    }
                }
            } else {
                $erro[] = 'Informe a data inicial';
            }
            // validando data_final
            if (isset($_POST['data_final'])){
                if (empty($_POST['data_final'])){
                    $erro[] = 'Informe a data final.';
                } else {
                    if (isDate($_POST['data_final']) == false){
                        $erro[] = 'A data final informada é inválida.';
                    }
                }
            } else {
                $erro[] = 'Informe a data final.';
            }
			
			// tudo ok?
			if (!isset($erro)){
				$mostra_frame_relatorio = true;
                log_sistema("Realizou uma busca nos logs do sistema.");
			}
		}
		?>
		<form name="frm_alterar" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_user" value="<?php echo @$_POST['id_user'] ?>" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td colspan="2" class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td colspan="2" class="bg_incluir">
            <?
			erro_bloco($erro);
            ?>
            </td>
        </tr>
            <?
		}
		?>
        <tr>
        <td colspan="2" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
        <tr>
        <td width="50%" class="bg_incluir">
            &nbsp;&nbsp;Data inicial *<br />
            &nbsp;&nbsp;<?php
            $array['name'] = "data_inicial";
            $array['id_form'] = "data_inicial";
            $array['size'] = 11;
            $array['max_caractere'] = 10;
            $array['value'] = isset($_POST['data_inicial']) ? $_POST['data_inicial'] : '';
            $array['id_img'] = 'f_data_inicial';
            $array['tipo_data'] = '%d/%m/%Y';
            form_input_date($array);
            unset($array);
            ?>
        </td>
        <td width="50%" class="bg_incluir">
            Data final *<br />
            <?php
            $array['name'] = "data_final";
            $array['id_form'] = "data_final";
            $array['size'] = 11;
            $array['max_caractere'] = 10;
            $array['value'] = isset($_POST['data_final']) ? $_POST['data_final'] : '';
            $array['id_img'] = 'f_data_final';
            $array['tipo_data'] = '%d/%m/%Y';
            form_input_date($array);
            unset($array);
            ?>
        </td>   
        </tr>
        <?
        if (isset($mostra_frame_relatorio)){
        ?>
        <tr>
        <td width="50%" colspan="2" class="bg_incluir">
          <iframe src="inc/blc/frame_logs_sistema.php?data_inicial=<?=$_POST['data_inicial']?>&data_final=<?=$_POST['data_final']?>" width="97%" height="500" frameborder="1" class="iframe_rel"></iframe>
        </td>
        </tr>
        <?  
        }
        ?>
        <tr>
        <td colspan="2" class="bg_incluir_p">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2" class="grid_topo" height="30" align="center">
		<input type="submit" value=" NOVA CONSULTA " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
			rules[0] = 'data_inicial|required|Informe a data incial.';
			rules[1] = 'data_inicial|date|A data inicial informada é inválida.';
            rules[2] = 'data_final|required|Informe a data final.';
			rules[3] = 'data_final|date|A data final informada é inválida.';
		</script>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		if (!isset($_POST['registro'])){
			header("Location: index.php?blc=".$blc."&acao=list");
			exit;
		}
		
        // registro
        if (isset($_POST['registro'])){
          if (!is_numeric($_POST['registro'])){
            $erro[] = 'Escolha um log para ser apagado do sistema.';
          }
        } else {
          $erro[] = 'Escolha um log para ser apagado do sistema.';
        }
		
		// tudo ok ?
		if (!isset($erro)){
			$id_log = normaltxt($_POST['registro']);
			
			// "deleta" log
			$sql_delete = "UPDATE logs_sistema SET ativo = 'N' WHERE id_log = '$id_log'";
			$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
            
            log_sistema("Deletou um registro no log do sistema.");
            
			header("Location: index.php?blc=".$blc."&acao=list");
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
