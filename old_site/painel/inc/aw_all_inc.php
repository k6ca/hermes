<?php
/**
*	Arquivo que inclue todos os outros necessário
*/

// Verificando se é include
$arq_bloco = 'aw_all_inc.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

require "inc/aw_apl.php";
require "inc/aw_geral.php";
require "inc/aw_conexao.php";
require "inc/aw_forms.php";
?>