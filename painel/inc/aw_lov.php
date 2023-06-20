<?php
session_start();
ob_start();

require "aw_all_inc_lov.php";

conexao();
$erro_lov = false;
// verificando se o get existe
if (!isset($_GET['q'])){
	echo '<script type="text/javascript">window.alert("Houve um erro ao processar o arquivo. ERRO: 001");window.close();</script>';
	exit;
}

// voltando o array codificado ao normal
$lov_decodificada = base64_decode($_GET['q']);
$lov = unserialize($lov_decodificada);

// verificando se o get é um array
if (!is_array($lov)){
	echo '<script type="text/javascript">window.alert("Houve um erro ao processar o arquivo. ERRO: 002");window.close();</script>';
	exit;
}

if (isset($lov['blc'])){
	if (access_bloco($lov['blc'], $_SESSION['user']['acesso'])==false){
		echo '<script type="text/javascript">window.alert("Você não tem permissão para acessar.");window.close();</script>';
		exit;
	}
}

// verificando se todo o array está correto
if (!isset($lov['size_lov_id']) || !is_numeric($lov['size_lov_id'])){
	$erro_lov = true;
}
if (!isset($lov['nome_lov_id'])){
	$erro_lov = true;
}
if (!isset($lov['nome_lov_desc'])){
	$erro_lov = true;
}
if (!isset($lov['size_lov_desc']) || !is_numeric($lov['size_lov_desc'])){
	$erro_lov = true;
}
if (!isset($lov['coluna_busca']) || !is_array($lov['coluna_busca'])){
	$erro_lov = true;
}
if (!isset($lov['tipo_busca']) || !is_array($lov['tipo_busca'])){
	$erro_lov = true;
}
if (!isset($lov['qtd_pg']) || !is_numeric($lov['qtd_pg'])){
	$erro_lov = true;
}
if (!isset($lov['colunas']) || !is_array($lov['colunas'])){
	$erro_lov = true;
}
if (!isset($lov['colunas_size']) || !is_array($lov['colunas_size'])){
	$erro_lov = true;
}
if (!isset($lov['select'])){
	$erro_lov = true;
}
if (substr($lov['select'],0,6) != 'SELECT'){
	$erro_lov = true;
}
if (!isset($lov['where'])){
	$erro_lov = true;
}
if (!isset($lov['order'])){
	$erro_lov = true;
}
if(!isset($lov['sql_count']) || substr($lov['sql_count'],0,6) != 'SELECT'){
	$erro_lov = true;
}
if(!isset($lov['sql_desc']) || substr($lov['sql_desc'],0,6) != 'SELECT'){
	$erro_lov = true;
}
if (!isset($lov['campo_id'])){
	$erro_lov = true;
}
if (!isset($lov['campo_desc'])){
	$erro_lov = true;
}
if (!isset($lov['v_id'])){
	$erro_lov = true;
}

// fechando janela e parando sistema caso der falha
if ($erro_lov == true){
	echo '<script type="text/javascript">window.alert("Houve um erro ao processar o arquivo. ERRO 003");window.close();</script>';
	exit;
}


// arrumando o where caso não exista
if (strlen($lov['where']) > 0){
	$lov['where'] = 'WHERE '.$lov['where'];
} else {
	$lov['where'] = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ESCOLHA UMA OPÇÃO</title>
<link href="../css/padrao/estilos.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="../js/aw_js.js"></script>
</head>

<body>
<?php

	$coluna_busca = $lov['coluna_busca'];
	
	$tipo_busca   = $lov['tipo_busca'];
	
	// Definindo o número de registros por página					
	$num_por_pagina = $lov['qtd_pg'];
	// Caso uma página ainda não estiver sido definida coloca valor 1
	$pag_atual = (isset($_POST['grid_pg'])) ? $_POST['grid_pg'] : 1;
	// achando o primeiro registro para a paginação
	$primeiro_registro = ($pag_atual * $num_por_pagina) - $num_por_pagina; 
	
		// SQL que faz a contagem total do número de registros
		if (!isset($_POST['grid'])){
			$sql_total = $lov['sql_count'];
			$sql_lista = $lov['select']." ".$lov['where']." ORDER BY ".$lov['order']." LIMIT $primeiro_registro, $num_por_pagina";
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
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 2:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo != '$txt_grid_txt'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo != '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 3:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo LIKE '".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 4:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."%' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 5:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo LIKE '%".$txt_grid_txt."' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 6:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo > '$txt_grid_txt'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo > '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				case 7:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo < '$txt_grid_txt'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo < '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
					break;
				default:
					$sql_total = $lov['sql_count']." WHERE $txt_grid_campo = '$txt_grid_txt'";
					$sql_lista = $lov['select']." WHERE $txt_grid_campo = '$txt_grid_txt' ORDER BY $txt_grid_campo ASC LIMIT $primeiro_registro, $num_por_pagina";
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
		
		$total_paginas = ceil($total_lista / $num_por_pagina);
		
		$prev = $pag_atual - 1;
		$next = $pag_atual + 1;
		
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']?>?q=<?php echo $_GET['q'] ?>" method="post" class="form_grid">
		<input type="hidden" name="grid" value="1" />
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
  			<tr class="grid_topo">
   			 <td width="70%">  
       		 <select name="grid_campo" class="campo_grid">
			 <?php
			 form_coluna_busca($lov['coluna_busca']);
			 ?>
        	 </select>
        	 <select name="grid_tipo" class="campo_grid">
        	 <?php
			 form_tipo_busca($lov['tipo_busca']);
			 ?>
        	 </select>
        	 <input type="text" name="grid_txt" class="campo_grid" size="12" value="<?php echo @$_POST['grid_txt'] ?>" />
			 <input type="submit" value="Buscar" class="campo_grid" />
</form>
      		 </td>
    		 <td width="30%" align="right">
			 <?php
			// se número total de páginas for maior que a página corrente, então temos link para a próxima página
			if ($total_paginas > $pag_atual) {
				$next_link = "<input type=\"button\" value=\" >> \" onClick=\"prev_pg('lov',".$next.",'paginacao')\" class=\"campo_grid\">";
			} else { // senão não há link para a próxima página
				$next_link = "<input type=\"button\" value=\" >> \" class=\"campo_grid\" disabled>";
			}
			 // se página maior que 1 (um), então temos link para a página anterior
			 if ($pag_atual > 1) {
		 		$prev_link = "<input type=\"button\" value=\" << \" onClick=\"prev_pg('lov',".$prev.",'paginacao')\" class=\"campo_grid\">";
	 		 } else { // senão não há link para a página anterior
				$prev_link = "<input type=\"button\" value=\" << \" class=\"campo_grid\" disabled>";
			 }
			 ?>
			 <!-- botão de voltar -->
			 <form action="<?php echo $_SERVER['PHP_SELF']?>?q=<?php echo $_GET['q'] ?>" method="post" class="form_grid" name="paginacao" id="paginacao">
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
			 <input type="button" value="Início" class="campo_grid" onclick="location.href = 'aw_lov.php?q=<?=$_GET['q']?>'" />
			 <?php
			 }
			 echo $prev_link;
			 echo $next_link;
			 ?>
			
			 </form>
			 
			 </td>
  		</tr>
		</table>
		
		<form action="aw_lov.php?q=<? echo $_GET['q'] ?>&acao=ok" method="post" class="form_grid" id="grid_grid">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
  		<tr>
			<td width="4%" class="top_mod_blc">&nbsp;</td>
			<?php
			// loop pra gerar nome das colunas com o tamanha
			$i_col = 0;
			foreach ($lov['colunas'] as $chave => $valor) {
				echo '<td width="'.$lov['colunas_size'][$i_col].'" class="top_mod_blc">&nbsp;'.$valor.'</td>';
				$i_col++;
			}
			unset($i_col);
			?>
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
		<td width="4%" class="<?php echo ($linha_bg % 2 == 0 ? "td_bga" : "td_bgb") ?>"><input type="radio" name="registro" value="<?php echo $reg_lista[$lov['campo_id']] ?>" style="margin:0; padding:0" <?php echo $checked?> /></td>
		<?
		// loop pra gerar o while
		foreach ($lov['colunas'] as $chave => $valor) {
			echo '<td class="'.($linha_bg % 2 == 0 ? "td_bga" : "td_bgb").'">&nbsp;'.stripslashes($reg_lista[$chave]).'</td>';
		}
		?>
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
			<input type="submit" value="Incluir" class="campo_grid" />
			</td>
  		</tr>
		</table>
		</form>
		<?php
		// definindo o campo
		if (isset($_GET['acao']) && $_GET['acao'] == 'ok' && isset($_POST['registro']) && is_numeric($_POST['registro'])){
			$sql_desc = $lov['sql_desc']." WHERE ".$lov['campo_id']." = ".normaltxt($_POST['registro']);
			$exe_desc = mysql_query($sql_desc, $base) or aw_error(mysql_error());
			$num_desc = mysql_num_rows($exe_desc);
			if ($num_desc == 1){
				$reg_desc = mysql_fetch_array($exe_desc, MYSQL_ASSOC);
			}
			
			if (isset($lov['opt'])){
				$_SESSION[$lov['blc']][$lov['opt']]['id'] = $_POST['registro'];
				$_SESSION[$lov['blc']][$lov['opt']]['desc'] = unhtmlentities($reg_desc[$lov['campo_desc']]);
			}
			?>
		<script type="text/javascript">
			if (window.opener.frm_incluir){
				window.opener.frm_incluir.<?=$lov['nome_lov_id']?>.value = <?=$_POST['registro']?>;
				window.opener.frm_incluir.<?=$lov['nome_lov_desc']?>.value = '<?=unhtmlentities($reg_desc[$lov['campo_desc']])?>';
			} else if (window.opener.frm_alterar){
				window.opener.frm_alterar.<?=$lov['nome_lov_id']?>.value = <?=$_POST['registro']?>;
				window.opener.frm_alterar.<?=$lov['nome_lov_desc']?>.value = '<?=unhtmlentities($reg_desc[$lov['campo_desc']])?>';
			}
			window.close();
		</script>
		<?php
		}
		?>
</body>
</html>
