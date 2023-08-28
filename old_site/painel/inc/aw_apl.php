<?php
/**
*	Arquivo : aw_apl.php
*	Define configurações do sistema
*/

// Verificando se é include
$arq_bloco = 'aw_apl.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

$config['sistema']      = "Hermes Empreendimentos";
$config['site_cliente'] = "www.hermes.eng.br";
$config['empresa']			= "Guigo Web Designer";
$config['site']					= "www.guigo.net";
$config['mail_autor']   = "rodurma@gmail.com";
$config['base'] 				= "http://www.hermes.eng.br";

/*
##$config['host']  				= "localhost";
##$config['user']  				= "root";
##$config['senha'] 				= "";
##$config['db']    				= "hermes_empreendimentos";
*/

$config['host']  				= "localhost";
$config['user']  				= "hermes";
$config['senha'] 				= "her12me512";
$config['db']    				= "hermeseng";

// 

// uploader do fckeditor
$config['fck_dir_upload'] = "/upload";

?>
