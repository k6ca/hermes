<?php
include "painel/inc/aw_apl.php";
include "painel/inc/aw_conexao.php";
include "painel/inc/aw_geral.php";
conexao();

$url = isset($_GET['_route_']) ? $_GET['_route_'] : '/';;

if (preg_match("/^\/$/", $url)){
  $pageTitle = 'Hermes Empreendimentos. Aluguel de apartamentos e quitinetes próximos a UFSC. Venda de imóveis em Florianópolis.';
  $mn['inicial'] = 1;
  include "engine/areas/inicial.php";
} elseif (preg_match("/^empresa[\/]?$/", $url)){
  $pageTitle = 'Sobre a Hermes Empreendimentos';
  $mn['empresa'] = 1;
  include "engine/areas/empresa.php";
} elseif (preg_match("/^contato[\/]?$/", $url)){
  $pageTitle = 'Hermes Empreendimentos. Fale conosco';
  $mn['contato'] = 1;
  include "engine/areas/contato.php";
} elseif (preg_match("/^aluguel[\/]?$/", $url)){
  $pageTitle = 'Hermes Empreendimentos. Aluguel de apartamentos e quitinetes próximos a UFSC.';
  $mn['aluguel'] = 1;
  include "engine/areas/aluguel.php";
} elseif (preg_match("/^venda[\/]?$/", $url)){
  $pageTitle = 'Hermes Empreendimentos. Imóveis à venda em Florianópolis e região';
  $mn['venda'] = 1;
  include "engine/areas/venda.php";
} elseif (preg_match("/^imovel\/(.*)-[0-9]+[\/]?$/", $url)){
  preg_match_all("/^imovel\/(.*)-([0-9]+)[\/]?$/", $url, $saida_url);
  if (!isset($saida_url[2][0])){
    header("Location: /");
    exit;
  }
  $_GET['id'] = $saida_url[2][0];
  include "engine/areas/imovel.php";
} else {
  $pageTitle = 'Erro 404';
  include "engine/areas/erro404.php";
}

?>