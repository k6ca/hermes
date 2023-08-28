<?php
session_start();
include "../aw_apl.php";
include "../aw_geral.php";
include "../aw_conexao.php";
conexao();

$ar_json = array(
  'erro' => ''
);

if (access_bloco('imoveis', $_SESSION['user']['acesso'])==false){
  $ar_json = array(
    'erro' => 'Não logado'
  );
} else {
  // verificando se tem turo
  if (!isset($_GET['img'], $_GET['id_imovel'])){
    $ar_json = array(
      'erro' => 'Não logado'
    );
  } else {
    $img = normaltxt($_GET['img']);
    $id_imovel = normaltxt($_GET['id_imovel']);
    $coluna_img = 'img'.$img;
    
    if (!in_array($img, array(1,2,3,4,5,6,7,8,9,10))){
      $ar_json = array(
        'erro' => 'Imagem inválida.'
      );
    }
    
    if ($ar_json['erro'] == ''){
      $sql_imovel = "SELECT * FROM imoveis WHERE id_imovel = '$id_imovel'";
      $exe_imovel = mysql_query($sql_imovel, $base) or die(json_encode(array('erro'=>'Erro no banco de dados.')));
      $num_imovel = mysql_num_rows($exe_imovel);
      if ($num_imovel > 0){
        $reg_imovel = mysql_fetch_array($exe_imovel, MYSQL_ASSOC);
        $sql_remove_img = "UPDATE imoveis SET img".$img." = '' WHERE id_imovel = '$id_imovel'";
        $exe_remove_img = mysql_query($sql_remove_img, $base) or die(json_encode(array('erro'=>'Erro no banco de dados.')));
        
        $ar_tamanhos = array(
          '75x56', '133x100', '164x123', '400x300', '800x600'
        );
        
        foreach($ar_tamanhos as $tam){
          if (file_exists("../../../imoveis/".$tam."/".$reg_imovel[$coluna_img])){
            unlink("../../../imoveis/".$tam."/".$reg_imovel[$coluna_img]);
          }  
        }
        
        
        
      } else {
        $ar_json = array(
          'erro' => 'Imóvel não encontrado.'
        );
      }

    }
    
  }

}


echo json_encode($ar_json);
?>