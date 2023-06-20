<?php
// Verificando se é include
$arq_bloco = 'aw_topo.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

// conectando no banco de dados
conexao();

//pr($_SESSION);

// incluindo arquivo que faz login.
require "inc/blc/aw_login.php";

// selecionando dados do aplicativo
$sql_dados_apl = "SELECT * FROM aw_apl WHERE id_apl = 1";
$exe_dados_apl = mysql_query($sql_dados_apl, $base) or aw_error(mysql_error());
$reg_dados_apl = mysql_fetch_array($exe_dados_apl, MYSQL_ASSOC);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $reg_dados_apl['nome_apl'] ?></title>
<link href="css/<?php echo $reg_dados_apl['estilo']?>/estilos.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/mask_money.js"></script>
<script type="text/javascript" src="js/aw_js.js"></script>
<script type="text/javascript" src="js/yav.js"></script>
<script type="text/javascript" src="js/yav-config.js"></script>
<!-- Arquivos pro calendário -->
<!-- estilo do calendário -->
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar_1_0/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<!-- arquivo principal do calendário -->
<script type="text/javascript" src="js/jscalendar_1_0/calendar.js"></script>
<!-- Linguagem do calendário -->
<script type="text/javascript" src="js/jscalendar_1_0/lang/calendar-en.js"></script>
<!-- funções do calendário -->
<script type="text/javascript" src="js/jscalendar_1_0/calendar-setup.js"></script>
</head>
<body>

<div id="geral">
<div id="topo">
<img src="img/logo_empresa.jpg" border="0" />
<?php
if (isset($_SESSION['user'])){
  ?>
  <p class="bem_vindo">
  <b>Usu&aacute;rio:</b>&nbsp;<?php echo $_SESSION['user']['nome']?><br />
  <?
  if (access_bloco('aw_senha', $_SESSION['user']['acesso'])==true){
  ?>
  <a href="index.php?blc=aw_senha&acao=list" class="link2">Alterar Senha</a> - 
  <? 
  }
  ?>
  <a href="<?php echo $_SERVER['PHP_SELF'] ?>?logout" class="link2">Sair</a>
  </p>  
  <?php
}
?> 
</div>
<div id="menu_sup">
<table cellpadding="0" cellspacing="0" id="tb_menu_sup">
	<tr>
		<?php
		// se não existir max da sessao de navegação então define como 0
		if (!isset($_SESSION['nav']['modulo']['max'])){
			$_SESSION['nav']['modulo']['max'] = 0;
		}

		// construindo o menu superior de acordo com as permissões do usuário
		if (isset($_SESSION['user'])){
			$sql_link_mod = "SELECT * FROM aw_modulo WHERE ativo = 'S' ORDER BY posicao ASC";
			$exe_link_mod = mysql_query($sql_link_mod, $base) or aw_error(mysql_error());
			if ($exe_link_mod){
				$i=1;
				while ($reg_link_mod = mysql_fetch_array($exe_link_mod, MYSQL_ASSOC)){
					$perm_mod = unserialize($reg_link_mod['perm_modulo']);

					if (!isset($_SESSION['user']['acesso'])){
						$acesso_mod = array('all');
					} else {
						$acesso_mod = $_SESSION['user']['acesso'];
					}
					if (isset($_GET['tab']) && $_GET['tab'] == $reg_link_mod['id_modulo']){
						$_SESSION['nav']['modulo']['tab'] = $reg_link_mod['id_modulo'];
						$_SESSION['nav']['modulo']['title'] = $reg_link_mod['descricao_modulo'];
						$_SESSION['nav']['modulo']['max'] = 0;
					} else {
						if (isset($_SESSION['nav']['modulo']['tab'])){
							$_SESSION['nav']['modulo']['tab'] = $_SESSION['nav']['modulo']['tab'];
						} else {
							$_SESSION['nav']['modulo']['tab'] = 1;
							$_SESSION['nav']['modulo']['title'] = "In&iacute;cio do Sistema";
						}
					}

					if (count($perm_mod) > 0){
						add_link_modulo($reg_link_mod['id_modulo'],$reg_link_mod['nome_modulo'],$perm_mod,$acesso_mod);
					}
					unset($perm_mod);
					$i++;
				}
			}
		} else {
			$_SESSION['nav']['modulo']['tab'] = 1;
			$_SESSION['nav']['modulo']['title'] = "In&iacute;cio do Sistema";
			$_SESSION['nav']['modulo']['max'] = 0;
			add_link_modulo(0,"In&iacute;cio",array('all'),array('all'));
		}

		?>
	</tr>
</table>
</div>
<div id="menu_modulo">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="50%">
		<?php
		// verificando se tab está setado
		if (!isset($_SESSION['nav']['modulo']['tab']) && !isset($_SESSION['nav']['modulo']['title'])){
			$_SESSION['nav']['modulo']['tab'] = 1;
			$_SESSION['nav']['modulo']['title'] = "In&iacute;cio do Sistema";
		}
		// adiciona o nome do módulo e nome do usuário e botão para sair do sistema
		if (isset($_SESSION['nav']['modulo']['title'])){
			echo $_SESSION['nav']['modulo']['title'];
		} else {
			echo '&nbsp;';
		}
		?>
		</td>
		<td width="50%" align="right"></td>
</table>
</div>
<?php
	// Verificando se o usuário tem de fato a permissão para o bloco em uso
	// verificando se o get foi enviado corretamente
	if (isset($_GET['blc'])){
		$blc = normaltxt($_GET['blc']);
	} else {
		$blc = "";
	}

	$sql_blc = "SELECT bloco, descricao, iniciar, hab_btn, layout FROM aw_bloco WHERE bloco = '$blc' AND ativo = 'S'";
	$exe_blc = mysql_query($sql_blc, $base) or aw_error("ERRO> ".mysql_error());
	$num_blc = mysql_num_rows($exe_blc);
	if ($num_blc > 0){
		$reg_blc = mysql_fetch_array($exe_blc, MYSQL_ASSOC);
	}

	// verificando se existe get max e ignorando

		if (!isset($_SESSION['nav']['modulo'][$blc])){
			if (isset($reg_blc)){
				if ($reg_blc['iniciar'] == 0){
					$_SESSION['nav']['modulo']['max'] = 0;
				} elseif ($reg_blc['iniciar'] == 1){
					$_SESSION['nav']['modulo']['max'] = 1;
				}
			}
		}

		if (isset($reg_blc)){
			if ($reg_blc['hab_btn'] == 'S'){
				if (isset($_GET['max'])){
					$_SESSION['nav']['modulo']['max'] = 1;
					$_SESSION['nav']['modulo'][$blc] = true;
					$_SESSION['nav']['max_min'][$blc] = 'max';
				} elseif (isset($_GET['min'])){
					$_SESSION['nav']['modulo']['max'] = 0;
					$_SESSION['nav']['modulo'][$blc] = true;
					$_SESSION['nav']['max_min'][$blc] = 'min';
				}

				if (isset($_SESSION['nav']['max_min'][$blc])){
					if ($_SESSION['nav']['max_min'][$blc] == 'max'){
						$_SESSION['nav']['modulo']['max'] = 1;
					}
					if ($_SESSION['nav']['max_min'][$blc] == 'min'){
						$_SESSION['nav']['modulo']['max'] = 0;
					}
				}
			}
		}

?>
