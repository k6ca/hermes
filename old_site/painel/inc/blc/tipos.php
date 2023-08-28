<?php	
	// Verificando se é include
	$arq_bloco = 'tipo.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){	
		exit;
  }
  
	$coluna_busca = array(
				'tipo'=>'Tipo'
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
			$sql_total = "SELECT count(*) as total_pag FROM tipos";
			$sql_lista = "SELECT * FROM tipos ORDER BY tipo LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'tipo';
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
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM tipos WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM tipos WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Tipo excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
    		<td width="80%" class="top_mod_blc">&nbsp;TIPO</td>
        <td width="16%" class="top_mod_blc">&nbsp;ATIVO</td>
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
        <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_tipo'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
    		<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['tipo'])?></td>
        <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?=($reg_lista['status'] == 'S') ? 'Ativo' : 'Inativo';?></td>
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
			// validando tipo
      if (isset($_POST['tipo'])){
          if (empty($_POST['tipo'])){
              $erro[] = 'Informe o tipo.';
          } else {
              if (strlen($_POST['tipo']) > 50){
                  $erro[] = 'Tipo com no máximo 50 caracteres.';
              }
          }
      } else {
          $erro[] = 'Informe o tipo.';
      }
      // validando status
      if (isset($_POST['status'])){
        if (empty($_POST['status']) || ($_POST['status'] != 'S' && $_POST['status'] != 'N')){
          $erro[] = 'Informe se está ativo ou não.';
        }
      } else {
        $erro[] = 'Informe está ativo ou não.';
      }  
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$tipo  = normaltxt($_POST['tipo']);
        $status = normaltxt($_POST['status']);
        
				
				$sql_insert = "INSERT INTO tipos (tipo, status) VALUES ('$tipo', '$status')";
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
        <td class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td class="bg_incluir">
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
          <td class="bg_incluir">
            <ul id="sucesso_sistema">
                <li>Tipo cadastrado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
		<tr>
        <td class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        
        </tr>
        <tr>
           <td width="34%" class="bg_incluir">
            &nbsp;&nbsp;Tipo *<br />
            &nbsp;&nbsp;<input name="tipo" type="text" id="tipo" size="50" maxlength="50" value="<?php echo @stripslashes($_POST['tipo'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Ativo *<br />
            &nbsp;&nbsp;<select name="status" class="campo_form">
              <?
              $ar_ativo = array('S'=>'Sim', 'N'=>'Não');
              foreach ($ar_ativo as $c => $v){
                if (@$_POST['status'] == $c){
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
        </tr>
        <tr>
        <td class="grid_topo" height="30" align="center">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
		</script>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			// verificando se aquele id existe
			$sql_ver = "SELECT count(*) as tem FROM tipos WHERE id_tipo = '".normaltxt($_POST['registro'])."'";
			$exe_ver = mysql_query($sql_ver, $base) or aw_error(mysql_error());
			$reg_ver = mysql_fetch_array($exe_ver, MYSQL_ASSOC);
			if ($reg_ver['tem'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;

			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM tipos WHERE id_tipo = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
			
			$_POST['id_tipo'] 	      = stripslashes($reg_select['id_tipo']);
      $_POST['tipo'] 	      = stripslashes($reg_select['tipo']);
      $_POST['status'] 	      = stripslashes($reg_select['status']);
		
		} else {
			// validando
			// id_tipo
			if (isset($_POST['id_tipo'])){
				if (empty($_POST['id_tipo']) || !is_numeric($_POST['id_tipo'])){
					$erro[] = 'Erro ao alterar.';
				}
			} else {
				$erro[] = 'Erro ao alterar.';
			}
			// validando tipo
      if (isset($_POST['tipo'])){
          if (empty($_POST['tipo'])){
              $erro[] = 'Informe o tipo.';
          } else {
              if (strlen($_POST['tipo']) > 50){
                  $erro[] = 'Tipo com no máximo 50 caracteres.';
              }
          }
      } else {
          $erro[] = 'Informe o tipo.';
      }
      // validando status
      if (isset($_POST['status'])){
        if (empty($_POST['status']) || ($_POST['status'] != 'S' && $_POST['status'] != 'N')){
          $erro[] = 'Informe se está ativo ou não.';
        }
      } else {
        $erro[] = 'Informe está ativo ou não.';
      }  
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
        $id_tipo = normaltxt($_POST['id_tipo']);
        $tipo  = normaltxt($_POST['tipo']);
        $status = normaltxt($_POST['status']);
				
				
				$sql_update = "UPDATE tipos SET 
                        tipo   = '$tipo', 
                        status      = '$status' 
                        WHERE
                        id_tipo        = '$id_tipo'
                        ";
				
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_tipo" value="<?php echo @$_POST['id_tipo'] ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
        <?php
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td class="bg_incluir">
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
          <td class="bg_incluir">
            <ul id="sucesso_sistema">
                <li>Tipo alterado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
		<tr>
        <td class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        
        </tr>
        <tr>
           <td width="34%" class="bg_incluir">
            &nbsp;&nbsp;Tipo *<br />
            &nbsp;&nbsp;<input name="tipo" type="text" id="tipo" size="50" maxlength="50" value="<?php echo @stripslashes($_POST['tipo'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir">
            &nbsp;&nbsp;Ativo *<br />
            &nbsp;&nbsp;<select name="status" class="campo_form">
              <?
              $ar_ativo = array('S'=>'Sim', 'N'=>'Não');
              foreach ($ar_ativo as $c => $v){
                if (@$_POST['status'] == $c){
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
        </tr>
        <tr>
        <td class="grid_topo" height="30" align="center">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
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
			$id_tipo = normaltxt($_POST['registro']);
			$sql_delete = "DELETE FROM tipos WHERE id_tipo = '$id_tipo'";
			$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());

			
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
