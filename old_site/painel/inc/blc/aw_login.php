<?php
/**
* Arquivo para login no sistema
*/

// Verificando se é include
$arq_bloco = 'aw_login.php';
// verificando se está acessando o arquivo diretamente.
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

// verificando se postou o form de login
if (isset($_POST['user']) && isset($_POST['senha']) && isset($_POST['submit'])){
	$login = normaltxt($_POST['user'], true);
	$senha = normaltxt($_POST['senha'], true);
	// verificando se o usuário existe

	$sql_login = "SELECT * FROM aw_user WHERE email = '$login' AND senha = PASSWORD('$senha') AND habilitado = 'S'";
	$exe_login = mysql_query($sql_login, $base) or aw_error(mysql_error());
	$num_login = mysql_num_rows($exe_login);
	if ($num_login == 1){
		$reg_login = mysql_fetch_array($exe_login, MYSQL_ASSOC);
		// gravando o ultimo acesso e o número de acessos
		$sql_login_update = "UPDATE aw_user SET ult_acesso = '".date("Y-m-d H:i:s")."', num_acessos = num_acessos + 1 WHERE id_user = '".$reg_login['id_user']."'";
		$exe_login_update = mysql_query($sql_login_update, $base) or aw_error(mysql_error());
		// definindo as sessions para serem usadas no sistema.
		$_SESSION['user']['id_user']	 	= $reg_login['id_user'];
		$_SESSION['user']['tipo'] 		 	= $reg_login['tipo'];
		$_SESSION['user']['nome'] 		 	= $reg_login['nome'];
		$_SESSION['user']['email']		 	= $reg_login['email'];
		$_SESSION['user']['acesso'] 	 	= pegar_permissao($reg_login['id_user']);
		$_SESSION['user']['ult_acesso']  	= $reg_login['ult_acesso'];
		$_SESSION['user']['num_acessos'] 	= $reg_login['num_acessos'];


		log_sistema("Efetuou login no sistema.");
	} else {
		//erro de login
		$erro_login = array("Usu&aacute;rio e/ou senha inv&aacute;lido(s)");
	}
}
?>
