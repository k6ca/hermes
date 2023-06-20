<?php
session_start();
ob_start();

date_default_timezone_set('America/Los_Angeles');
// inclui arquivo com includes gerais
require "inc/aw_all_inc.php";

// fazendo logout
if (isset($_GET['logout'])){
   conexao();
   // gerando log no sistema
   log_sistema("Saiu do sistema.");
   // destruindo a sessao
   session_unset();
   session_destroy();
   header("Location: /");
   exit;
}

// inclui arquivo aw_topo.php
require "inc/aw_topo.php";
// inclui arquivo aw_menu.php
require "inc/aw_menu.php";
// inclui arquivo aw_blocos.php
require "inc/aw_blocos.php";
// inclui arquivo aw_rodape.php
require "inc/aw_rodape.php";


?>
