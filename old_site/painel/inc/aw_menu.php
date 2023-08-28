<?php
/**
* Arquivo que gerencia os menus e também a caixa de login
*/
// Verificando se é include
$arq_bloco = 'aw_menu.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<?php
if ($_SESSION['nav']['modulo']['max'] == 0){
?>
<td width="220" height="460" valign="top" id="menu_lateral_bloco">
<?php
if (!isset($_SESSION['user']['id_user'])){
?>
	<div id="login">
		<p>Conexão no Sistema</p>
		<?php
		if (isset($erro_login) && count($erro_login) > 0){
			echo "<ul id=\"erro_login\">";
			for ($i_erro_login = 0;$i_erro_login < count($erro_login);$i_erro_login++){
				echo "<li>".$erro_login[$i_erro_login]."</li>";
			}
			echo "</ul>";
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<div id="login_a">
		Usuário<br />
		<input name="user" type="text" class="campo" id="user" value="<?php echo @stripslashes($_POST['user']) ?>" />
		<br />
		Senha<br />
		<input name="senha" type="password" class="campo" id="senha" value="<?php echo @stripslashes($_POST['senha']) ?>" />
		<br />
		</div>
		<div id="login_b">
		<input name="submit" type="submit" class="submit" id="submit" value="Entrar &raquo;" />
		</div>
		<input type="hidden" name="flogin" value="1" />
		</form>
	</div>
<?php
} else {
	$sql_menu_modulo = "SELECT aw_bloco.*, aw_modulo.ativo FROM aw_bloco, aw_modulo WHERE aw_bloco.id_modulo = '".$_SESSION['nav']['modulo']['tab']."' AND aw_bloco.ativo = 'S' AND aw_bloco.oculto = 'N' AND aw_bloco.id_modulo = aw_modulo.id_modulo AND aw_modulo.ativo = 'S' ORDER BY posicao ASC";
	$exe_menu_modulo = mysql_query($sql_menu_modulo) or aw_error(mysql_error());
			
	if ($exe_menu_modulo){
		$num_menu_modulo = mysql_num_rows($exe_menu_modulo);
		if ($num_menu_modulo > 0){
			echo '<div id="main_menu">';
			echo '<ul>';
			while($reg_menu_modulo = mysql_fetch_array($exe_menu_modulo, MYSQL_ASSOC)){
				if (strlen($reg_menu_modulo['link']) > 0){
					$link = $reg_menu_modulo['link'];
				} else {
					$link = false;
				}
				add_link_bloco($reg_menu_modulo['bloco'],$reg_menu_modulo['nome'],$_SESSION['user']['acesso'], $link);
			}
			echo '</ul>';
			echo '</div>';
		} else {
		?>
		<script type="text/javascript">
				// esconde menu se não tiver módulos
				esconde_menu();
		</script>
		<?
		}
	}
	
}
?>
</td>
<?php
}
?>