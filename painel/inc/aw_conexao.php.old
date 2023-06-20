<?php
/**
*	Arquivo que gera funчуo de conexуo com o banco de dados
*	Criado : 04/01/2006
*/

// Verificando se щ include
$arq_bloco = 'aw_conexao.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

function conexao(){
	global $config, $base;
	$base = mysql_connect($config['host'],$config['user'],$config['senha']) or aw_error("Erro ao conectar ao banco de dados");
	$db   = mysql_select_db($config['db'],$base) or aw_error("Erro ao conectar ao banco de dados");
}

// conexao com o banco de dados mu classic
function conexao_mu(){
  global $config, $base_mu;
  $erro_conexao = 0;
  @$base_mu = mssql_connect($config['host_mu'],$config['user_mu'],$config['senha_mu']) or $erro_conexao++;
  @$db_mu   = mssql_select_db($config['db_mu'], $base_mu) or $erro_conexao++;
  
  if ($erro_conexao == 0){
    return true;
  } else {
    return false;
  }
  
}

// conexao com o banco de dados mu xp alta
function conexao_xpmu(){
  global $config, $base_xpmu;
  $erro_conexao = 0;
  @$base_xpmu = mssql_connect($config['host_xpmu'],$config['user_xpmu'],$config['senha_xpmu']) or $erro_conexao++;
  @$db_xpmu   = mssql_select_db($config['db_xpmu'], $base_xpmu) or $erro_conexao++;
  
  if ($erro_conexao == 0){
    return true;
  } else {
    return false;
  }
  
}

// conexao com o banco de dados eudemons
function conexao_eo(){
  global $config, $base_eo;
  $erro_conexao = 0;
  @$base_eo = mysql_connect($config['host_eo'],$config['user_eo'],$config['senha_eo']) or $erro_conexao++;
  @$db_eo   = mysql_select_db($config['db_eo'], $base_eo) or $erro_conexao++;
  
  if ($erro_conexao == 0){
    return true;
  } else {
    return false;
  }
  
}

// conexao com o banco de dados contribui
function conexao_contribui(){
  global $config, $base_contribui;
  $erro_conexao = 0;
  @$base_contribui = mysql_connect($config['host_contribui'],$config['user_contribui'],$config['senha_contribui']) or $erro_conexao++;
  @$db_contribui   = mysql_select_db($config['db_contribui'], $base_contribui) or $erro_conexao++;
  
  if ($erro_conexao == 0){
    return true;
  } else {
    return false;
  }
  
}

// conexao com o banco de dados comunidade
function conexao_comunidade(){
  global $config;
  global $base_comunidade;
  
  $erro_comunidade = 0;
  
  @$base_comunidade = mysql_connect($config['host_comunidade'],$config['user_comunidade'],$config['senha_comunidade']) or die(mysql_error());
  @$db_comunidade   = mysql_select_db($config['db_comunidade'],$base_comunidade) or die(mysql_error());
  
  if ($erro_comunidade == 0){
	return true;
  } else {
	return false;
  }
}
?>