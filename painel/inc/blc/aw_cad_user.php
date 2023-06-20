<?php
	// Verificando se é include
	$arq_bloco = 'aw_cad_user.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
		exit;
	}
	
	$coluna_busca = array(
						 'nome'=>'Nome',
						 'email'=>'E-Mail',
             'habilitado'=>'Ativo (S/N)'
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
			$sql_total = "SELECT count(*) as total_pag FROM aw_user";
			$sql_lista = "SELECT * FROM aw_user ORDER BY nome LIMIT $primeiro_registro, $num_por_pagina";
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
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM aw_user WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM aw_user WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Usuário excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
			<td width="35%" class="top_mod_blc">&nbsp;NOME</td>
			<td width="35%" class="top_mod_blc">&nbsp;E-MAIL</td>
			<td width="14%" class="top_mod_blc">&nbsp;Nº ACESSOS</td>
      <td width="12%" class="top_mod_blc">&nbsp;ATIVO</td>
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
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id_user'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['nome'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['email'])?></td>
			<td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo stripslashes($reg_lista['num_acessos'])?></td>
            <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>">&nbsp;<?php echo ($reg_lista['habilitado'] == 'S') ? 'Sim' : 'Não' ?></td>
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
			// validando txt_nome
			if (isset($_POST['txt_nome'])){
			    if (empty($_POST['txt_nome'])){
				$erro[] = 'Informe um nome.';
			    } else {
				if (strlen($_POST['txt_nome']) > 100){
				    $erro[] = 'O nome deve conter no máximo 100 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe um nome.';
			}
			// validando txt_email
			if (isset($_POST['txt_email'])){
			    if (empty($_POST['txt_email'])){
				$erro[] = 'Informe um e-mail.';
			    } else {
				if (isMail($_POST['txt_email']) == false){
				    $erro[] = 'Informe um e-mail válido.';
				}
			    }
			} else {
			    $erro[] = 'Informe um e-mail.';
			}
			// validando txt_senha
			if (isset($_POST['txt_senha'])){
				if (empty($_POST['txt_senha'])){
					$erro[] = 'Informe a senha.';
				} else {
					if (strlen($_POST['txt_senha']) > 40){
						$erro[] = 'Senha com no máximo 40 caracteres.';
					}
				}
			} else {
				$erro[] = 'Informe a senha.';
			}
            // txt_senha, txt_resenha
            if (isset($_POST['txt_senha'], $_POST['txt_resenha'])){
              if ($_POST['txt_senha'] != $_POST['txt_resenha']){
                $erro[] = 'A confirmação de senha não corresponde a senha digitada.';
              }
            } else {
              $erro[] = 'Confirme a senha.';
            }
			
			
			// verificando se habilitado
			if (!isset($_POST['habilitado']) || ($_POST['habilitado'] != 'S' && $_POST['habilitado'] != 'N')){
				$erro[] = "Informe se o usuário está habilitado ou não.";
			}
			
			// validando permissões
			if (!isset($_POST['perm'])){
				$erro[] = "Informe as permissões desse usuário.";
			}
			
            if (!isset($erro)){
              $sql_ver_user = "SELECT count(id_user) as tem_email FROM aw_user WHERE email = '".normaltxt($_POST['txt_email'])."'";
              $exe_ver_user = mysql_query($sql_ver_user, $base) or aw_error(mysql_error());
              $reg_ver_user = mysql_fetch_array($exe_ver_user, MYSQL_ASSOC);
              if ($reg_ver_user['tem_email'] >= 1){
                  $erro[] = "Já existe um usuário cadastrado com esse e-mail.";
              } 
            }
			
			// tudo ok ?
			if (!isset($erro)){
				$txt_nome     = normaltxt($_POST['txt_nome']);
				$txt_email    = normaltxt($_POST['txt_email']);
				$tipo 	      = 0;
				$senha 	      = normaltxt($_POST['txt_senha'],true);
				$data_cadastro = date("Y-m-d H:i:s");
				$ult_acesso    = date("Y-m-d H:i:s");
				$num_acessos   = 0;
				$habilitado    = $_POST['habilitado'];
	
				
				$sql_insert = "INSERT INTO aw_user (tipo, senha,  nome, email, data_cadastro, ult_acesso, num_acessos, habilitado) VALUES 
							  ('$tipo', PASSWORD('$senha'),'$txt_nome','$txt_email','$data_cadastro','$ult_acesso','$num_acessos','$habilitado')";
				$exe_insert = mysql_query($sql_insert, $base) or aw_error(mysql_error());
                
                // gerando string de acesso
				$sql_perm = "SELECT bloco  FROM aw_bloco WHERE ";
				sort($_POST['perm']);
				for ($i_p=0;$i_p<count($_POST['perm']);$i_p++){
					if ($i_p == (count($_POST['perm']) - 1)){
						$sql_perm .= "id_bloco = ".$_POST['perm'][$i_p]; 
					} else {
						$sql_perm .= "id_bloco = ".$_POST['perm'][$i_p]." OR "; 
					}
				}
				$exe_perm = mysql_query($sql_perm, $base) or aw_error(mysql_error());
				$num_perm = mysql_num_rows($exe_perm);
  
				while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
						$acesso_[] = $reg_perm['bloco'];
				}
				$acesso_[] = "all"; 
				$permissoes = $acesso_;
                
                $id_do_usuario = mysql_insert_id();
                
                add_permissao($id_do_usuario, $permissoes);
                
				
				unset($_POST);
                
                log_sistema("incluiu um novo usuário no sistema.");
                
                $sucesso = true;
			}
		}
		?>
		<form name="frm_incluir" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Incluir" method="post" onsubmit="return performCheck('frm_incluir', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
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
                <li>Usuário cadastrado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
        <tr>
        <td colspan="2" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
        <tr>
        <td width="50%" class="bg_incluir">&nbsp;&nbsp;Nome *<br />&nbsp;&nbsp;<input name="txt_nome" type="text" id="txt_nome" size="40" maxlength="80" value="<?php echo @$_POST['txt_nome']?>" />    </td>
        <td width="50%" class="bg_incluir">E-mail *<br /><input name="txt_email" type="text" id="txt_email" size="40" maxlength="150" value="<?php echo @$_POST['txt_email']?>" />		</td>
        </tr>
        <tr>
        <td class="bg_incluir">&nbsp;&nbsp;Senha *<br />
		&nbsp;&nbsp;<input name="txt_senha" type="password" id="txt_senha" size="40" maxlength="40" value="<?php echo @$_POST['txt_senha']?>" /></td>
        <td class="bg_incluir">Confirmar Senha *<br />
		<input name="txt_resenha" type="password" id="txt_resenha" size="40" maxlength="40" value="<?php echo @$_POST['txt_resenha']?>" /></td>
        </tr>
	<tr>
        <td class="bg_incluir">&nbsp;&nbsp;Habilitado *<br />
          &nbsp;&nbsp;<select name="habilitado" id="habilitado">
					<?php
					if (isset($_POST['habilitado']) && $_POST['habilitado'] == 'S'){
						$habilitado_s = 'selected="selected"';
						$habilitado_n = '';
					} elseif (isset($_POST['habilitado']) && $_POST['habilitado'] == 'N'){
						$habilitado_s = '';
						$habilitado_n = 'selected="selected"';
					} else {
						$habilitado_s = 'selected="selected"';
						$habilitado_n = '';
					}
					?>
            <option value="S" <?php echo $habilitado_s?>>Sim</option>
            <option value="N" <?php echo $habilitado_n?>>N&atilde;o</option>
          </select>
	</td>
        <td class="bg_incluir">          </td>
        </tr>
        <tr>
          <td colspan="2" class="bg_incluir">
            <div class="tit_display">Permissões Para Esse Usuário *</div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="bg_incluir" align="center">
		  	<table cellpadding="0" cellspacing="0" border="0" width="96%">
				<tr>
		  <?php
			if ($_SESSION['user']['id_user'] == 1){
				$sql_perm = "SELECT * FROM aw_bloco WHERE ativo = 'S' ORDER BY id_modulo ASC";
			} else {
				$sql_perm = "SELECT * FROM aw_bloco WHERE ativo = 'S' AND sistema = 'N' ORDER BY id_modulo ASC";
			}
		  	
			$exe_perm = mysql_query($sql_perm, $base) or aw_error(mysql_error());
			$num_perm = mysql_num_rows($exe_perm);
			if ($num_perm > 0){
				$i_perm = 0;
				while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
					$i_perm++;
					
					if ($i_perm == 0){
						echo '<tr>';
					}
					if (isset($_POST['perm']) && in_array($reg_perm['bloco'], $_POST['perm'])){
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
		  </table></td>
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
		<script type="text/javascript">
			var rules = new Array();
			rules[0] = 'txt_nome|required|Coloque um nome.';
			rules[1] = 'txt_email|required|Informe um e-mail válido.';
			rules[2] = 'txt_email|email|E-mail inválido.';
			rules[3] = 'txt_senha|required|Informe a senha.';
			rules[4] = 'txt_senha|maxlength|40|Senha com no máximo 40 caracteres.';
      rules[5] = 'txt_resenha|required|Confirme a senha.';
			rules[6] = 'txt_tipo|required|Informe o tipo de cadastro.';
			rules[7] = 'perm[]|required|Escolha pelo menos uma permissão.';
		</script>
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			// verificando se aquele id existe
			$sql_ver_user = "SELECT count(*) as tem_login FROM aw_user WHERE id_user = '".normaltxt($_POST['registro'])."'";
			$exe_ver_user = mysql_query($sql_ver_user, $base) or aw_error(mysql_error());
			$reg_ver_user = mysql_fetch_array($exe_ver_user, MYSQL_ASSOC);
			if ($reg_ver_user['tem_login'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;
			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM aw_user WHERE id_user = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);
			$_POST['txt_nome']    = stripslashes($reg_select['nome']);
			$_POST['txt_tipo']    = stripslashes($reg_select['tipo']);
			$_POST['txt_email']   = stripslashes($reg_select['email']);
      $_POST['email_atual'] = stripslashes($reg_select['email']);
			$_POST['perm']        = pegar_permissao($reg_select['id_user']);
			
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
			$_POST['habilitado'] = stripslashes($reg_select['habilitado']);
			$_POST['id_user'] = $_POST['registro'];
		} else {
			// validando id_user
			if (isset($_POST['id_user'])){
				if (empty($_POST['id_user']) || !is_numeric($_POST['id_user'])){
					$ero[] = 'Erro ao alterar usuário.';
				}
			} else {
				$ero[] = 'Erro ao alterar usuário.';
			}
			// validando txt_nome
			if (isset($_POST['txt_nome'])){
			    if (empty($_POST['txt_nome'])){
				$erro[] = 'Informe um nome.';
			    } else {
				if (strlen($_POST['txt_nome']) > 100){
				    $erro[] = 'O nome deve conter no máximo 100 caracteres.';
				}
			    }
			} else {
			    $erro[] = 'Informe um nome.';
			}
			// validando txt_email
			if (isset($_POST['txt_email'])){
			    if (empty($_POST['txt_email'])){
				$erro[] = 'Informe um e-mail.';
			    } else {
				if (isMail($_POST['txt_email']) == false){
				    $erro[] = 'Informe um e-mail válido.';
				}
			    }
			} else {
			    $erro[] = 'Informe um e-mail.';
			}
			// se não tiver erro e foi mudado o email verificamos se o
            // novo e-mail já existe no banco de dados
			if (isset($_POST['txt_email'], $_POST['email_atual'])){
              if ($_POST['txt_email'] != $_POST['email_atual']){
                $novo_email = normaltxt($_POST['txt_email']);
                $sql_tem_email = "SELECT COUNT(*) AS tem_email FROM aw_user WHERE email = '$novo_email'";
                $exe_tem_email = mysql_query($sql_tem_email, $base) or aw_error(mysql_error());
                $reg_tem_email = mysql_fetch_array($exe_tem_email, MYSQL_ASSOC);
                if ($reg_tem_email['tem_email'] > 0){
                  $erro[] = "Já existe um usuário cadastrado com esse e-mail.";
                }
              }
            }
			// txt_senha
			if (isset($_POST['txt_senha'])){
				if (!empty($_POST['txt_senha'])){
					if (strlen($_POST['txt_senha']) > 40){
						$erro[] = 'Senha com no máximo 40 caracteres.';
					}
				}
			} else {
				$erro[] = 'Informe a senha.';
			}
            // txt_senha, txt_resenha
            if (isset($_POST['txt_senha'], $_POST['txt_resenha'])){
              if (!empty($_POST['txt_senha'])){
                if ($_POST['txt_senha'] != $_POST['txt_resenha']){
                  $erro[] = 'A confirmação de senha não corresponde a senha digitada.';
                }
              }
            } else {
              $erro[] = 'Confirme a senha.';
            }
			
			// validando habilitado
			if (isset($_POST['habilitado'])){
				if (empty($_POST['habilitado'])){
					$erro[] = 'Informe se o usuário está habilitado ou não.';
				} else {
					if ($_POST['habilitado'] != 'S' && $_POST['habilitado'] != 'N'){
						$erro[] = 'Informe se o usuário está habilitado ou não.';
					}
				}
			} else {
				$erro[] = 'Informe se o usuário está habilitado ou não.';
			}
			
			// validando permissões
			if (!isset($_POST['perm'])){
				$erro[] = "Informe as permissões desse usuário.";
			}
            
			// tudo ok?
			if (!isset($erro)){
        $id_user = normaltxt($_POST['id_user']);
				$txt_nome   = normaltxt($_POST['txt_nome']);
				$tipo 	    = 0;
				$txt_email  = normaltxt($_POST['txt_email']);
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
				
                deletar_todas_permissoes($id_user);
                add_permissao($id_user, $acesso_);
                
				$habilitado = $_POST['habilitado'];
				
				if (!empty($_POST['txt_senha'])){
					$senha = normaltxt($_POST['txt_senha'], true);
					$sql_update = "UPDATE aw_user SET
							   tipo = '$tipo',
							   senha = PASSWORD('$senha'), 
							   nome = '$txt_nome',
							   email = '$txt_email',
							   habilitado = '$habilitado' 
							   WHERE id_user = '$id_user'
							   ";
				} else {
					$sql_update = "UPDATE aw_user SET
							   tipo = '$tipo', 
							   nome = '$txt_nome',
							   email = '$txt_email',
							   habilitado = '$habilitado' 
							   WHERE id_user = '$id_user'
							   ";
				}

				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
                
        log_sistema("Alterou um usuário do sistema (id: ".$id_user.").");
                
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id_user" value="<?php echo @$_POST['id_user'] ?>" />                                                                             
        <input type="hidden" name="email_atual" value="<?php echo @$_POST['email_atual'] ?>" />
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
                <li>Usuário alterado com sucesso.</li>
            </ul>
          </td>
        </tr>
        <?
        endif;
        ?>
        <tr>
        <td colspan="2" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
        <tr>
        <td width="50%" class="bg_incluir">&nbsp;&nbsp;Nome *<br />&nbsp;&nbsp;<input name="txt_nome" type="text" id="txt_nome" size="40" maxlength="80" value="<?php echo @$_POST['txt_nome']?>" />    </td>
        <td width="50%" class="bg_incluir">E-mail *<br /><input name="txt_email" type="text" id="txt_email" size="40" maxlength="150" value="<?php echo @$_POST['txt_email']?>" />		</td>
        </tr>
        <tr>
        <td class="bg_incluir">&nbsp;&nbsp;Senha *<br />
		&nbsp;&nbsp;<input name="txt_senha" type="password" id="txt_senha" size="40" maxlength="40" value="<?php echo @$_POST['txt_senha']?>" /></td>
        <td class="bg_incluir">Confirmar Senha *<br />
		<input name="txt_resenha" type="password" id="txt_resenha" size="40" maxlength="40" value="<?php echo @$_POST['txt_resenha']?>" /></td>
        </tr>
	<tr>
        <td class="bg_incluir">&nbsp;&nbsp;Habilitado *<br />
          &nbsp;&nbsp;<select name="habilitado" id="habilitado">
		  <?php
		  if (isset($_POST['habilitado']) && $_POST['habilitado'] == 'S'){
		  	$habilitado_s = 'selected="selected"';
			$habilitado_n = '';
		  } elseif (isset($_POST['habilitado']) && $_POST['habilitado'] == 'N'){
		  	$habilitado_s = '';
			$habilitado_n = 'selected="selected"';
		  } else {
		  	$habilitado_s = 'selected="selected"';
			$habilitado_n = '';
		  }
		  ?>
            <option value="S" <?php echo $habilitado_s?>>Sim</option>
            <option value="N" <?php echo $habilitado_n?>>N&atilde;o</option>
          </select>
	</td>
        <td class="bg_incluir">          </td>
        </tr>
	<tr>
          <td colspan="2" class="bg_incluir">
		  <div class="tit_display">Permissões Para Esse Usuário *</div>		  </td>
        </tr>
        <tr>
          <td colspan="2" class="bg_incluir" align="center">
		  	<table cellpadding="0" cellspacing="0" border="0" width="96%">
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
		  </table>		  </td>
        </tr>
        <tr>
        <td colspan="2" class="bg_incluir_p">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2" class="grid_topo" height="30" align="center">
		<input type="submit" value=" ALTERAR " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			var rules = new Array();
			rules[0] = 'txt_nome|required|Coloque um nome.';
			rules[1] = 'txt_email|required|Informe um e-mail válido.';
			rules[2] = 'txt_email|email|E-mail inválido.';
			rules[3] = 'txt_senha|maxlength|40|Senha com no máximo 40 caracteres.';
			rules[4] = 'txt_tipo|required|Informe o tipo de cadastro.';
			rules[5] = 'perm[]|required|Escolha pelo menos uma permissão.';
		</script>
		<?php
	}  elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Excluir')){
		if (!isset($_POST['registro'])){
			header("Location: index.php?blc=".$blc."&acao=list");
			exit;
		}
		if (!isset($_POST['registro']) || !is_numeric($_POST['registro'])){
			$erro[] = "Escolha um usuário do sistema para apagar.";
		}
		if (!isset($_POST['registro']) || ($_POST['registro'] == $_SESSION['user']['id_user'])){
			$erro[] = "Você não pode se apagar.";
		}
		
		$sql_is_admin = "SELECT count(*) AS admin FROM aw_user WHERE id_user = '".normaltxt($_POST['registro'])."' AND id_user = 1";
		$exe_is_admin = mysql_query($sql_is_admin, $base) or aw_error(mysql_error());
		$reg_is_admin = mysql_fetch_array($exe_is_admin, MYSQL_ASSOC);
		if ($reg_is_admin['admin'] > 0){
			$erro[] = "Usuário Administrador não pode ser deletado.";
		}
		
		// tudo ok ?
		if (!isset($erro)){
			$id_user = normaltxt($_POST['registro']);
			$sql_delete = "DELETE FROM aw_user WHERE id_user = '$id_user'";
			$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
            
            deletar_todas_permissoes($id_user);
            
            log_sistema("Deletou um usuário do sistema (id: ".$id_user.").");
            
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
