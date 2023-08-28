<?php	
	// Verificando se é include
	$arq_bloco = 'slider.php';
	if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){	
		exit;
  }
	
	require "libs/imgresize/hft_image.php";
  
	$coluna_busca = array(
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
		
		// selecionando o total geral para ver se tem mais de 6 itens
		$sql_num_ban = "SELECT COUNT(1) AS total_slider FROM slider";
		$exe_num_ban = mysql_query($sql_num_ban, $base) or aw_error(mysql_error());
		$reg_num_ban = mysql_fetch_array($exe_num_ban, MYSQL_ASSOC);
		$total_banners = $reg_num_ban['total_slider'];
	
		// SQL que faz a contagem total do número de registros
		if (!isset($_POST['grid'])){
			$sql_total = "SELECT count(*) as total_pag FROM slider";
			$sql_lista = "SELECT * FROM slider ORDER BY id LIMIT $primeiro_registro, $num_por_pagina";
		} else {
			if (!isset($_POST['grid_campo']) || !isset($_POST['grid_tipo']) || !isset($_POST['grid_txt'])){
				$txt_grid_campo = 'id';
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
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = "SELECT count(*) as total_pag FROM slider WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = "SELECT * FROM slider WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
          <li>Banner excluído.</li>
        </ul>
        <?
        endif;
        ?>
		<form action="#" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
        <td width="4%" class="top_mod_blc">&nbsp;</td>
				<td width="86%" class="top_mod_blc">&nbsp;TÍTULO</td>
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
        <td class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista['id'] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
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
			<? if ($total_banners < 6): ?>
			<input name="button" type="button" class="campo_grid" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>?blc=<?php echo $blc ?>&acao=Incluir'" value="Cadastrar" />
			<? endif; ?>
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
			// validando titulo
			if (isset($_POST['titulo'])){
				if (empty($_POST['titulo'])){
					$erro[] = 'Informe o título.';
				} elseif (strlen($_POST['titulo']) > 250){
					$erro[] = 'O título deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o título.';
			}
			// validando subtitulo
			if (isset($_POST['subtitulo'])){
				if (empty($_POST['subtitulo'])){
					$erro[] = 'Informe o subtítulo.';
				} elseif (strlen($_POST['subtitulo']) > 250){
					$erro[] = 'O subtítulo deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o subtítulo.';
			}
			// validando descricao
			if (isset($_POST['descricao'])){
				if (empty($_POST['descricao'])){
					$erro[] = 'Informe a descrição.';
				} elseif (strlen($_POST['descricao']) > 250){
					$erro[] = 'A descrição deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição.';
			}
			// img
			if (isset($_FILES['img']['name'], $_FILES['img']['size'], $_FILES['img']['tmp_name'], $_FILES['img']['type'])){
				if (empty($_FILES['img']['name'])){
          $erro[] = "Informe a imagem.";
        } else {
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img']['type']),-3) != 'gif' && substr(strtolower($_FILES['img']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img']['tmp_name']) || is_dir($_FILES['img']['name']) || getimagesize($_FILES['img']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img']['tmp_name']);
						if ($wh[0] != 865 || $wh[1] != 300){
							$erro[] = 'A imagem deve ter as dimensões 865px de largura por 300px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem.";
			}
			// validando link
			if (isset($_POST['link'])){
				if (empty($_POST['link'])){
					$erro[] = 'Informe o link.';
				}
			} else {
				$erro[] = 'Informe o link.';
			}
			// validando ativo
			if (isset($_POST['ativo'])){
				if (empty($_POST['ativo']) || !in_array($_POST['ativo'], array('S', 'N'))){
					$erro[] = 'Informe se está ou não está ativo.';
				}
			} else {
				$erro[] = 'Informe se está ou não está ativo.';
			}
			// verificando o total de banners
			$sql_ban = "SELECT COUNT(1) AS total_ban FROM slider";
			$exe_ban = mysql_query($sql_ban, $base) or aw_error(mysql_error());
			$reg_ban = mysql_fetch_array($exe_ban, MYSQL_ASSOC);
			if ($reg_ban['total_ban'] == 6){
				$erro[] = 'Já foram cadastrados o máximo de 6 banners.';
			}
			
			// tudo ok ?
			if (!isset($erro)){
				// inclui aqui
				$titulo			= normaltxt($_POST['titulo']);
				$subtitulo	= normaltxt($_POST['subtitulo']);
				$descricao	= normaltxt($_POST['descricao']);
				$link				= normaltxt($_POST['link']);
				$ativo			= normaltxt($_POST['ativo']);
				
				
				// img
				$id_img = gen_numero('slider');
				if (substr($_FILES['img']['name'],-3) == 'jpg' || substr($_FILES['img']['name'],-3) == 'JPG'){
					$ext = ".jpg";
				} elseif (substr($_FILES['img']['name'],-3) == 'gif' || substr($_FILES['img']['name'],-3) == 'GIF'){
					$ext = ".gif";
				} else {
					$ext = ".jpg";
				}
				$img_ = $id_img.$ext;
				$img = new hft_image($_FILES['img']['tmp_name']);
				$img->resize(865,300,"-");
				$img->output_resized("../slider/".$img_);
        
				
				$sql_insert = "INSERT INTO slider (titulo, subtitulo, descricao, link, img, ativo) VALUES ('$titulo', '$subtitulo', '$descricao', '$link', '$img_', '$ativo')";
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
                <li>Banner cadastrado com sucesso.</li>
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
            &nbsp;&nbsp;Título *<br />
            &nbsp;&nbsp;<input name="titulo" type="text" id="titulo" size="80" maxlength="250" value="<?php echo @stripslashes($_POST['titulo'])?>" />
           </td>
        </tr>
				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Subtítulo *<br />
            &nbsp;&nbsp;<input name="subtitulo" type="text" id="subtitulo" size="80" maxlength="250" value="<?php echo @stripslashes($_POST['subtitulo'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Descrição *<br />
            &nbsp;&nbsp;<textarea name="descricao" id="descricao" style="width: 600px; height: 80px;"><?php echo @stripslashes($_POST['descricao'])?></textarea>
           </td>
        </tr>

				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Imagem (865x300) *<br />
            &nbsp;&nbsp;<input type="file" name="img" size="40" />
           </td>
					 
				</tr>
				
				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Link *<br />
            &nbsp;&nbsp;<input name="link" type="text" id="link" size="80" value="<?php echo @stripslashes($_POST['link'])?>" />
           </td>
        </tr>
				
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Ativo *<br />
            &nbsp;&nbsp;<?=input_select('ativo', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>    
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
		<?php
	} elseif (isset($_GET['acao']) && ($_GET['acao'] == 'Alterar')){
		if (!isset($_POST['form_submit'])){
			
			// verificando se aquele id existe
			$sql_ver = "SELECT count(*) as tem FROM slider WHERE id = '".normaltxt($_POST['registro'])."'";
			$exe_ver = mysql_query($sql_ver, $base) or aw_error(mysql_error());
			$reg_ver = mysql_fetch_array($exe_ver, MYSQL_ASSOC);
			if ($reg_ver['tem'] == 0){
				header("Location: index.php?blc=".$blc."&acao=list");
				exit;

			}
			
			// selecionando os dados
			$sql_select = "SELECT * FROM slider WHERE id = '".normaltxt($_POST['registro'])."'";
			$exe_select = mysql_query($sql_select, $base) or aw_error(mysql_error());
			$reg_select = mysql_fetch_array($exe_select, MYSQL_ASSOC);

			$_POST['id']				= stripslashes($reg_select['id']);
			$_POST['titulo']		= stripslashes($reg_select['titulo']);
			$_POST['subtitulo']	= stripslashes($reg_select['subtitulo']);
			$_POST['descricao']	= stripslashes($reg_select['descricao']);
			$_POST['link']			= stripslashes($reg_select['link']);
			$_POST['img']				= stripslashes($reg_select['img']);
			$_POST['ativo']			= stripslashes($reg_select['ativo']);
		
		} else {
			// validando
			// id
			if (isset($_POST['id'])){
				if (empty($_POST['id']) || !is_numeric($_POST['id'])){
					$erro[] = 'Erro ao alterar.';
				}
			} else {
				$erro[] = 'Erro ao alterar.';
			}
			// validando titulo
			if (isset($_POST['titulo'])){
				if (empty($_POST['titulo'])){
					$erro[] = 'Informe o título.';
				} elseif (strlen($_POST['titulo']) > 250){
					$erro[] = 'O título deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o título.';
			}
			// validando subtitulo
			if (isset($_POST['subtitulo'])){
				if (empty($_POST['subtitulo'])){
					$erro[] = 'Informe o subtítulo.';
				} elseif (strlen($_POST['subtitulo']) > 250){
					$erro[] = 'O subtítulo deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe o subtítulo.';
			}
			// validando descricao
			if (isset($_POST['descricao'])){
				if (empty($_POST['descricao'])){
					$erro[] = 'Informe a descrição.';
				} elseif (strlen($_POST['descricao']) > 250){
					$erro[] = 'A descrição deve conter no máximo 250 caracteres.';
				}
			} else {
				$erro[] = 'Informe a descrição.';
			}
			// img
			if (isset($_FILES['img']['name'], $_FILES['img']['size'], $_FILES['img']['tmp_name'], $_FILES['img']['type'])){
				if (!empty($_FILES['img']['name'])){
					if (preg_match("/[][><}{)(:;,!?*%&#@]/", $_FILES['img']['name'])) {  //checa caracteres inválidos (aconselho não modificar)
						$erro[] = "O nome da imagem contém caracteres inválidos.";
						$erro_img = true;
					}
					if ($_FILES['img']['size'] > 2000000) {  //checa se o arquivo não ultrapassou o limite
						$erro[] = "Imagem é maior que 1 MB.";
						$erro_img = true;
					}
					if (substr(strtolower($_FILES['img']['type']),-4) != 'jpeg' && substr(strtolower($_FILES['img']['type']),-3) != 'jpg' && substr(strtolower($_FILES['img']['type']),-3) != 'gif' && substr(strtolower($_FILES['img']['type']),-3) != 'jpg') { //checa a extensão do arquivo - para liberar mais tipos, apenas acrescente "|extensão do arquivo" ex: [gif|jpeg|jpg|png]
							$erro[] = "A imagem deve ser no formato JPG ou GIF.";
							$erro_img = true;
					}
					if (!is_file($_FILES['img']['tmp_name']) || is_dir($_FILES['img']['name']) || getimagesize($_FILES['img']['tmp_name']) == false) { //checa se é mesmo um arquivo
						$erro[] = "O arquivo escolhido como imagem é inválido.";
						$erro_img = true;
					}
					if (!isset($erro_img)){
						$wh = getimagesize($_FILES['img']['tmp_name']);
						if ($wh[0] != 865 || $wh[1] != 300){
							$erro[] = 'A imagem deve ter as dimensões 865px de largura por 300px de altura.';
						}
					}	
				}
			} else {
				$erro[] = "Informe a imagem.";
			}
			// validando link
			if (isset($_POST['link'])){
				if (empty($_POST['link'])){
					$erro[] = 'Informe o link.';
				}
			} else {
				$erro[] = 'Informe o link.';
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
				$id					= normaltxt($_POST['id']);
				$titulo			= normaltxt($_POST['titulo']);
				$subtitulo	= normaltxt($_POST['subtitulo']);
				$descricao	= normaltxt($_POST['descricao']);
				$link				= normaltxt($_POST['link']);
				$ativo			= normaltxt($_POST['ativo']);
				
				$sql_aux = '';
				
				// img
				if (!empty($_FILES['img']['name'])){
					$id_img = gen_numero('slider');
					if (substr($_FILES['img']['name'],-3) == 'jpg' || substr($_FILES['img']['name'],-3) == 'JPG'){
						$ext = ".jpg";
					} elseif (substr($_FILES['img']['name'],-3) == 'gif' || substr($_FILES['img']['name'],-3) == 'GIF'){
						$ext = ".gif";
					} else {
						$ext = ".jpg";
					}
					$img_ = $id_img.$ext;
					// 800x600
					$img = new hft_image($_FILES['img']['tmp_name']);
					$img->resize(865,300,"-");
					$img->output_resized("../slider/".$img_);
					
					if (isset($_POST['img'])){
						if (!empty($_POST['img'])){
							if (file_exists("../slider/".$_POST['img'])){
								unlink("../slider/".$_POST['img']);
							}
						}
					}
					
					$sql_aux .= ", img = '$img_'";
					$_POST['img'] = $img_;
					
				}
				
				$sql_update = "UPDATE slider SET 
												titulo = '$titulo', 
												subtitulo = '$subtitulo', 
												descricao = '$descricao', 
												link = '$link', 
												ativo = '$ativo'
												$sql_aux
												WHERE id = '$id'
                        ";
				$exe_update = mysql_query($sql_update, $base) or aw_error(mysql_error());
				
				$sucesso = true;
			}
		}
		?>
		<form name="frm_alterar" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onsubmit="return performCheck('frm_alterar', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<input type="hidden" name="id" value="<?php echo @$_POST['id'] ?>" />
		<input type="hidden" name="img" value="<?php echo @$_POST['img'] ?>" />
		
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
                <li>Banner alterado com sucesso.</li>
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
            &nbsp;&nbsp;Título *<br />
            &nbsp;&nbsp;<input name="titulo" type="text" id="titulo" size="80" maxlength="250" value="<?php echo @stripslashes($_POST['titulo'])?>" />
           </td>
        </tr>
				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Subtítulo *<br />
            &nbsp;&nbsp;<input name="subtitulo" type="text" id="subtitulo" size="80" maxlength="250" value="<?php echo @stripslashes($_POST['subtitulo'])?>" />
           </td>
        </tr>
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Descrição *<br />
            &nbsp;&nbsp;<textarea name="descricao" id="descricao" style="width: 600px; height: 80px;"><?php echo @stripslashes($_POST['descricao'])?></textarea>
           </td>
        </tr>

				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Imagem (865x300) *<br />
            &nbsp;&nbsp;<input type="file" name="img" size="40" />
						<? if (file_exists("../slider/".$_POST['img'])):?>
						<div style="margin:10px">
							<img src="/slider/<?=$_POST['img']?>" />
						</div>
						<? endif; ?>
           </td>
					 
				</tr>
				
				<tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Link *<br />
            &nbsp;&nbsp;<input name="link" type="text" id="link" size="80" value="<?php echo @stripslashes($_POST['link'])?>" />
           </td>
        </tr>
				
        <tr>
           <td class="bg_incluir" colspan="2">
            &nbsp;&nbsp;Ativo *<br />
            &nbsp;&nbsp;<?=input_select('ativo', array(''=>'', 'S'=>'Sim','N'=>'Não'))?>    
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
			$id = normaltxt($_POST['registro']);
			
			$sql_slider = "SELECT * FROM slider WHERE id = '$id'";
			$exe_slider = mysql_query($sql_slider, $base) or aw_error(mysql_error());
			$num_slider = mysql_num_rows($exe_slider);
			if ($num_slider > 0){
				$reg_slider = mysql_fetch_array($exe_slider, MYSQL_ASSOC);
				
				if (!empty($reg_slider['img'])){
					if (file_exists("../slider/".$reg_slider['img'])){
						unlink("../slider/".$reg_slider['img']);
					}	
				}
				
				$sql_delete = "DELETE FROM slider WHERE id = '$id'";
				$exe_delete = mysql_query($sql_delete, $base) or aw_error(mysql_error());
				
				header("Location: index.php?blc=".$blc."&acao=list&sucesso");
				exit;	
			} else {
				$erro[] = 'Banner não encontrado.';
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
