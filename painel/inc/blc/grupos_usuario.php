<?php
	// Verificando se é include
	$arq_bloco = 'grupos_usuario.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
		exit;
	}

	$coluna_busca = array(
						 'nome'   =>'Grupo'
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
			$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario";
			$sql_lista = "SELECT * FROM grupos_usuario ORDER BY nome LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'nome';
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
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM grupos_usuario WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM grupos_usuario WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Funcionário excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
			<td width="96%" class="top_mod_blc">&nbsp;GRUPO</td>
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
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['nome'])?></td>
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
			<input type="button" value="Alterar permissões" class="campo_grid" onclick="gen_submit('<?php echo $blc?>','alt','grid_grid')" <?=$btn_crud?> />
          </td>
  		</tr>
		</table>
		</form>
		<?php
		
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Incluir')){
		// não tem
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			// verificando se aquele id existe
			$sql_ver_user = "SELECT count(*) as tem FROM grupos_usuario WHERE id = '".normaltxt($_POST['registro'])."'";
			$exe_ver_user = mysql_query($sql_ver_user, $base) or aw_error(mysql_error());
			$reg_ver_user = mysql_fetch_array($exe_ver_user, MYSQL_ASSOC);
			if ($reg_ver_user['tem'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;
			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM grupos_usuario WHERE id = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
            
			$_POST['id']    = stripslashes($reg_select['id']);
            $_POST['nome']  = stripslashes($reg_select['nome']);
            $_POST['perm']  = unserialize($reg_select['permissoes']);
            
            // acessos;
			sort($_POST['perm']);
			
			$sql_perm = "SELECT id_bloco FROM aw_bloco WHERE ";
			for ($i_p=0;$i_p<count($_POST['perm']);$i_p++){
				if ($i_p == (count($_POST['perm']) - 1)){
					$sql_perm .= "bloco = '".$_POST['perm'][$i_p]."'"; 
				} else {
					$sql_perm .= "bloco = '".$_POST['perm'][$i_p]."' OR "; 
				}
			}
			unset($_POST['perm']);
			$exe_perm = mysql_query($sql_perm, $base) or aw_error(mysql_error());
			$num_perm = mysql_num_rows($exe_perm);
			while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
				$_POST['perm'][] = $reg_perm['id_bloco'];
			}

		} else {
			// validando id
			if (isset($_POST['id'])){
				if (empty($_POST['id']) || !is_numeric($_POST['id'])){
					$erro[] = 'Erro ao alterar permissões deste grupo.';
				}
			} else {
				$erro[] = 'Erro ao alterar permissões deste grupo.';
			}
            // nome
            if (isset($_POST['nome'])){
              if (empty($_POST['nome'])){
                $erro[] = 'Informe o nome.';
              }
            } else {
              $erro[] = 'Informe o nome.';
            }
			// perm
            if (isset($_POST['perm'])){
              if (empty($_POST['perm'])){
                $erro[] = 'Informe as permissões desse grupo.';
              } else {
                if (!is_array($_POST['perm'])){
                  $erro[] = 'Informe as permissões desse grupo.';
                }
              }
            } else {
              $erro[] = 'Informe as permissões desse grupo.';
            }
            
			// tudo ok?
			if (!isset($erro)){
				// para a segunda parte do cadastro
                $id     = normaltxt($_POST['id']);
                $nome   = normaltxt($_POST['nome']);
                
                // gerando string de acesso
				$sql_perm = "SELECT bloco FROM aw_bloco WHERE ";
				for ($i_p=0;$i_p<count($_POST['perm']);$i_p++){
					if ($i_p == (count($_POST['perm']) - 1)){
						$sql_perm .= "id_bloco = ".$_POST['perm'][$i_p]; 
					} else {
						$sql_perm .= "id_bloco = ".$_POST['perm'][$i_p]." OR "; 
					}
				}
				
				$exe_perm = mysql_query($sql_perm, $base) or aw_error('AQUI = '.mysql_error());
				$num_perm = mysql_num_rows($exe_perm);
				while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
					$acesso_[] = $reg_perm['bloco'];	
				}
				$acesso_[] = "all";
				$permissoes = serialize($acesso_);
				

                $sql_update = "UPDATE grupos_usuario SET
                                permissoes  = '$permissoes' 
                               WHERE
                                id    = '$id'
                              ";
      
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
                
                /*
                // pegando os usuarios cadastrados neste grupo
                $sql_user = "SELECT id_user, acesso FROM aw_user WHERE tipo = '$id'";
                $exe_user = mysql_query($sql_user, $base) or aw_error(mysql_error());
                $num_user = mysql_num_rows($exe_user);
                if ($num_user > 0){
                  while ($reg_user = mysql_fetch_array($exe_user, MYSQL_ASSOC)){
                    $novo_acesso = array_merge($acesso_, unserialize($reg_user['acesso']));
                    $sql_alt_acesso_user = "UPDATE aw_user SET acesso = '".$novo_acesso."' WHERE id_user = '".$reg_user['id_user']."'";
                    $exe_alt_acesso_user = mysql_query($sql_alt_acesso_user, $base) or aw_error(mysql_error());
                  }
                }
                */
                
                log_sistema("Alterou alterou as permissões do grupo ".$nome);
                
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id" value="<?php echo @$_POST['id'] ?>" />
        <input type="hidden" name="nome" value="<?php echo @$_POST['nome'] ?>" />
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
        <?
        if (isset($sucesso)):
        ?>
        <tr>
          <td colspan="2" class="bg_incluir">
            <ul id="sucesso_sistema">
                <li>Permissões do grupo alteradas com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
        <tr>
        <td colspan="2" class="bg_incluir">
          <ul id="info_sistema">
            <li>Escolha as permissões para o grupo <?=$_POST['nome']?>.</li>
          </ul>
        </td>
        </tr>
        
        <tr>
          <td colspan="2" class="bg_incluir">
            <table cellpadding="0" cellspacing="0" border="0" width="96%" style="margin-left:8px;">
			<tr>
            <?php
              $sql_perm = "SELECT * FROM aw_bloco WHERE ativo = 'S' ORDER BY id_modulo ASC";
              $exe_perm = mysql_query($sql_perm, $base) or aw_error(mysql_error());
              $num_perm = mysql_num_rows($exe_perm);
              if ($num_perm > 0){
                  $i_perm = 0;
                  while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
                      $i_perm++;
                      if ($i_perm == 0){
                          echo '<tr>';
                      }
                      if (isset($_POST['perm']) && in_array($reg_perm['id_bloco'], $_POST['perm'])){
                          $checked = "checked=\"checked\"";
                      } else {
                          $checked = "";
                      }
                  ?>
                  <td width="32%" align="left">
                  <input type="checkbox" name="perm[]" value="<?php echo $reg_perm['id_bloco'] ?>" <?php echo $checked ?> /><span title="<?php echo stripslashes($reg_perm['descricao']) ?>"><?php echo stripslashes($reg_perm['nome']) ?></span><br />				</td>
                  <?php
                      if ($i_perm == 3){
                          echo '</tr>';
                          $i_perm = 0;
                      }
                      
                  }
              }
            ?>
            </table>
          </td> 
        </tr>
	
        <tr>
        <td colspan="2" class="bg_incluir_p">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2" class="grid_topo" height="30" align="center">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		// não tem
	}
	
?>
