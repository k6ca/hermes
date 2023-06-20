<?php
/**
*	Arquivo que inclue todos os outros necessário
*/

// Verificando se é include
$arq_bloco = 'aw_all_inc_lov.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

require "aw_apl.php";
require "aw_geral.php";
require "aw_conexao.php";
require "aw_forms.php";
?>