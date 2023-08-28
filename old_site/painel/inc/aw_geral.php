<?php
// Verificando se é include
$arq_bloco = 'aw_geral.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

function limita_chars($texto, $limite){
	if (strlen($texto) > $limite){
		return substr($texto, 0, $limite)."...";
	} else {
		return $texto;
	}
}

// funç&atilde;o que verifica erros de programaç&atilde;o
function aw_error($msg){
	
	echo '
	<script>
		janela_erro = window.open("","erro","width=450, height=250");
		janela_erro.document.write("<div style=\"position:absolute;top:0;left:0;width:100%;height:230px;font-family:verdana; font-size:11px;font-weight: bold;color:red\">'.$msg.'</div>");
	</script>
	';
	
	exit;
}

// funç&atilde;o que arruma string para consulta no banco de dados
function normaltxt($str,$upperlower = false, $html = false){
	if ($upperlower == true){
		if ($html == true){
			return addslashes(trim(strtoupper($str)));
		} else {
			return addslashes(htmlentities(trim(strtoupper($str))));
		}
	} else {
		if ($html == true){
			return addslashes(trim($str));
		} else {
			return addslashes(htmlentities(trim($str)));
		}
	}
}




// deixa data padrao mysql
function data_mysql($data){
	$data = explode("/",$data);
	return $data[2]."-".$data[1]."-".$data[0];
}

// funç&atilde;o que adiciona um link pra módulo de acordo com a permiss&atilde;o do usuário
function add_link_modulo($id_mod,$label,$permissoes_mod,$permissoes_user){
	$show = false;

	sort($permissoes_mod);
	sort($permissoes_user);

	for ($i=0;$i<count($permissoes_mod);$i++){
		if (isset($permissoes_mod[$i])){
			if (in_array($permissoes_mod[$i],$permissoes_user)){
				$show = true;
			}
		}
	}
	if ($show == true){
      echo '<td class="td_menu_sup"><a href="'.$_SERVER['PHP_SELF'].'?tab='.$id_mod.'">'.stripslashes($label).'</a></td>'; 
	}
}

// funç&atilde;o que adiciona link de um bloco
function add_link_bloco($blc,$label,$perm_user,$link=false){
	if (in_array($blc, $perm_user)){
		if ($link != false){
			echo '<li><a href="'.$link.'" target="_blank">'.stripslashes($label).'</a></li>';
		} else {
			if (isset($_GET['blc'])){
				if ($blc == $_GET['blc']){
					echo '<li><a href="'.$_SERVER['PHP_SELF'].'?blc='.$blc.'&acao=list" class="bloco_at">'.stripslashes($label).'</a></li>';
				} else {
					echo '<li><a href="'.$_SERVER['PHP_SELF'].'?blc='.$blc.'&acao=list">'.stripslashes($label).'</a></li>';
				}
			} else {
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?blc='.$blc.'&acao=list">'.stripslashes($label).'</a></li>';
			}
		}

	}
}

// function access_bloco()
function access_bloco($blc, $perm_user){
	if (isset($perm_user) && in_array($blc, $perm_user)){
		return true;
	} else {
		return false;
	}
}


// adiciona bloco em branco
function bloco_branco(){
	if (!isset($_SESSION['user'])){
		// include de página inicial sem estar logado
	} else {
		// include de página inicial em branco
	}
}


// retorna erro
function erro_bloco($erro){
	$n_erros = count($erro);
	echo '<ul id="erro_sistema">';
	for($i=0;$i<$n_erros;$i++){
		echo '<li>'.$erro[$i].'</li>';
	}
	echo '</ul>';
}

// retorna sucesso
function sucesso_bloco($erro){
	$n_erros = count($erro);
	echo '<ul id="sucesso_sistema">';
	for($i=0;$i<$n_erros;$i++){
		echo '<li>'.$erro[$i].'</li>';
	}
	echo '</ul>';
}

/**
*	Funções para controle de módulo
*/

function form_coluna_busca($colunas){
	$n_colunas = count($colunas);
	for($i=0;$i<$n_colunas;$i++){
		if (isset($_POST['grid_campo']) && $_POST['grid_campo'] == key($colunas)){
			echo '<option value="'.key($colunas).'" selected="selected">'.$colunas[key($colunas)].'</option>';
		} else {
			echo '<option value="'.key($colunas).'">'.$colunas[key($colunas)].'</option>';
		}
		next($colunas);
	}
}

function form_tipo_busca($tipo){
	$n_tipo = count($tipo);
    
    if (!isset($_POST['grid_tipo'])){
      $_POST['grid_tipo'] = 4;
    }
    
	for($i=0;$i<$n_tipo;$i++){
		if (isset($_POST['grid_tipo']) && $_POST['grid_tipo'] == key($tipo)){
			echo '<option value="'.key($tipo).'" selected="selected">'.$tipo[key($tipo)].'</option>';
		} else {
			echo '<option value="'.key($tipo).'">'.$tipo[key($tipo)].'</option>';
		}
		next($tipo);
	}
}

function formata_dinheiro($valor){
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", ".", $valor);
	return $valor;
}

function input_select($name, $values){
	echo '<select name="'.$name.'">';
	foreach($values as $c => $v){
		if (@$_POST[$name] == $c){
			echo '<option value="'.$c.'" selected="selected">'.$v.'</option>';
		} else {
			echo '<option value="'.$c.'">'.$v.'</option>';
		}
	}
	echo '</select>';
}


// Funç&atilde;o para validar email
function isMail($email){
	$er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
	if (preg_match($er, $email)){
		return true;
	} else {
		return false;
	}
}

// funç&atilde;o que verifica se é data
function isDate($data){
	$data = explode("/",$data);
	if ((count($data) < 3) || (count($data) >3)){
		return false;
	} else {
	  if ((isset($data[0])) && (isset($data[1])) && (isset($data[2])) && (strlen($data[0]) > 0) && (strlen($data[1]) > 0) && (strlen($data[2]) > 0)){
		if (checkdate($data[1],$data[0],$data[2]) == true){
			return true;
		} else {
			return false;
		}
	  }	else {
	  	return false;
	  }
	}
}

// arrumada data pro banco
function data_padrao($data){
	$data = explode("/",$data);
	$data = $data[2]."-".$data[1]."-".$data[0];
	return $data;
}

// transforma data do banco do formato YYYY-mm-dd 00:00:00 pra dd/mm/YYYY
function datetime2datebr($data){

	$data   = explode(" ",$data);
	$data_1 = explode("-",$data[0]);
	$data_2 = $data_1[2]."/".$data_1[1]."/".$data_1[0];

	return $data_2;
}

// transforma data do banco do formato YYYY-mm-dd 00:00:00 pra dd/mm/YYYY H;m;s
function datetime2datetimebr($data){

	$data   = explode(" ",$data);
	$data_d = explode("-",$data[0]);
	$data_2 = $data_d[2]."/".$data_d[1]."/".$data_d[0]." ".$data[1];

	return $data_2;
}

function datetime2datetimebr2($data){

	$data   = explode(" ",$data);
	$data_d = explode("-",$data[0]);
	$data_2 = "Em ".$data_d[2]."/".$data_d[1]."/".$data_d[0]." às ".$data[1];

	return $data_2;
}

// transforma data do banco do formato YYYY-mm-dd pra dd/mm/YYYY
function date2datebr($data){

	$data   = explode("-",$data);
	$data_1 = $data[2]."/".$data[1]."/".$data[0];

	return $data_1;

}

/**
* Funç&atilde;o para validar CPF (Cadastro de Pessoas Físicas)
*
* @author    Paulo Ricardo F. Santos <v1d4l0k4.at.gmail.dot.com>
* @copyright Copyright &copy; 2006, Paulo Ricardo F. Santos
* @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative
* @version   1.3.20060131
* @param     string $cpf o CPF que deseja validar
* @return    bool   true caso seje válido, false caso n&atilde;o seje válido
*/

function check_cpf($cpf)
{
	$d1 = 0;
	$d2 = 0;

   $cpf = ereg_replace('[^0-9]', '', $cpf);

   $ignore_list = array('00000000000',
                        '01234567890',
                        '11111111111',
                        '22222222222',
                        '33333333333',
                        '44444444444',
                        '55555555555',
                        '66666666666',
                        '77777777777',
                        '88888888888',
                        '99999999999');

   if(strlen($cpf) != 11 || in_array($cpf, $ignore_list))
   {

       return FALSE;

   } else {

       for($i = 0; $i < 9; $i++) $d1 += $cpf[$i] * (10 - $i);

       $r1 = $d1 % 11;

       $d1 = ($r1 > 1) ? (11 - $r1)
                       : 0;

       for($i = 0; $i < 9; $i++) $d2 += $cpf[$i] * (11 - $i);

       $r2 = ($d2 + ($d1 * 2)) % 11;

       $d2 = ($r2 > 1) ? (11 - $r2)
                       : 0;

       return (substr($cpf, -2) == $d1 . $d2) ? TRUE
                                              : FALSE;
   }
}

/*
Funç&atilde;o desenvolvida por: Paulo Ricardo F. Santos [v1d4l0k4]
Contato: v1d4l0k4[at]gmail[dot]com
*/

function CheckUF($uf){
$uf=ereg_replace("[^A-Za-z]","",$uf);
$valid_list=array("AC","AL","AM","AP","BA","CE","DF","ES","GO","MA","MG","MS",
"MT","PA","PB","PE","PI","PR","RJ","RN","RO","RR","RS","SC","SE","SP","TO");
if(strlen($uf)==2&&in_array(strtoupper($uf),$valid_list))return true;else return false;
}

/**
* Funç&atilde;o para validar CNPJ (Cadastro Nacional da Pessoa Jurídica)
*
* @author    Paulo Ricardo F. Santos <v1d4l0k4.at.gmail.dot.com>
* @copyright Copyright &copy; 2006, Paulo Ricardo F. Santos
* @license   http://creativecommons.org/licenses/by-nc-sa/2.0/br Commons Creative
* @version   1.1.20060131
* @param     string $cnpj o CNPJ que deseja validar
* @return    bool   true caso seje válido, false caso n&atilde;o seje válido
*/

function check_cnpj($cnpj)
{
   $d1 = 0;
   $d2 = 0;

   $cnpj = ereg_replace('[^0-9]', '', $cnpj);

   $ignore_list = array('00000000000000');

   if (strlen($cnpj) != 14 || in_array($cnpj, $ignore_list))
   {

       return FALSE;

   } else {

       $m1 = 2;

       for($i = 11; $i >- 1; $i--)
       {
           $d1 += $cnpj{$i} * $m1;
           $m1  = ($m1 < 9) ? ++$m1
                            : 2;
       }

       $r1 = $d1 % 11;

       $d1 = ($r1 > 1) ? 11 - $r1
                       : 0;

       $m2 = 3;

       for($i = 11; $i >- 1; $i--)
       {
           $d2 += $cnpj{$i} * $m2;
           $m2 = ($m2 < 9) ? ++$m2
                           : 2;
       }

       $r2 = ($d2 + ($d1 * 2)) % 11;

       $d2 = ($r2 > 1) ? 11 - $r2
                       : 0;

       return (substr($cnpj, -2) == $d1 . $d2) ? TRUE
                                               : FALSE;
   }
}

// funç&atilde;o que desfaz htmlentities()
function unhtmlentities ($string)
{
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);
}

// contador de pageviews do sistema
function aw_contador($total = false){
	global $base;
	$dia_hora = date("Y-m-d H");
	$retorno = array();
	// verificando se ja tem registro do dia e hora
	$sql_cont = "SELECT * FROM aw_contador WHERE date_format(datahora, '%Y-%m-%d %H') = '$dia_hora'";
	$exe_cont = mysql_query($sql_cont, $base) or aw_error("Erro na funç&atilde;o aw_contador() : ".mysql_error());
	$num_cont = mysql_num_rows($exe_cont);
	if ($num_cont > 0){
		$reg_cont = mysql_fetch_array($exe_cont, MYSQL_ASSOC);
		$num_show = $reg_cont['pageviews'] + 1;
		$sql_cont_alt = "UPDATE aw_contador SET pageviews = pageviews + 1 WHERE date_format(datahora, '%Y-%m-%d %H') = '$dia_hora'";
	} else {
		$num_show = 1;
		$sql_cont_alt = "INSERT INTO aw_contador (datahora, pageviews) VALUES ('".date("Y-m-d H:i:s")."', 1)";
	}

	$exe_cont_alt = mysql_query($sql_cont_alt, $base) or aw_error("ERRO na alteraç&atilde;o do número na funç&atilde;o aw_contador() : ".mysql_error());

	$retorno['por_hora'] = $num_show;

	if ($total == true){
		$sql_total = "SELECT SUM(pageviews) as total_pageviews FROM aw_contador";
		$exe_total = mysql_query($sql_total, $base) or aw_error("Erro ao pegar total de pageviews na funç&atilde;o aw_contador() : ".mysql_error());
		$reg_total = mysql_fetch_array($exe_total, MYSQL_ASSOC);
		$retorno['total'] = $reg_total['total_pageviews'];
	}

	return $retorno;
}

// remove acento
function removeacento($str)
{
  $from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàá&atilde;âéêíóõôúüç';
  $to   = 'AAAAEEIOOOUUCaaaaeeiooouuc';

  return strtr($str, $from, $to);
}

function gera_senha($size){
	$keys = array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','v','x','z','y','w','2','3','4','5','6','7','8','9');
	shuffle($keys);
	
	return substr(implode("",$keys),0,$size);
}

function gen_numero($blc){
	global $base;

	$sql_gen_numero = "SELECT numero FROM gen_numero WHERE bloco = '$blc'";
	$exe_gen_numero = mysql_query($sql_gen_numero, $base) or aw_error("Erro ao gerar numero : ".mysql_error());
	$num_gen_numero = mysql_num_rows($exe_gen_numero);

	if ($num_gen_numero > 0){

		$reg_gen_numero = mysql_fetch_array($exe_gen_numero, MYSQL_ASSOC);

		$sql_up_numero = "UPDATE gen_numero SET numero = numero + 1 WHERE bloco = '$blc'";
		$exe_up_numero = mysql_query($sql_up_numero, $base) or aw_error(mysql_error());

		return $reg_gen_numero['numero'] + 1;

	} else {

		$sql_novo_numero = "INSERT INTO gen_numero (`bloco`, `numero`) VALUES ('$blc', '1')";
		$exe_novo_numero = mysql_query($sql_novo_numero, $base) or aw_error(mysql_error());

		return 1;

	}
}

define("TRUNC_BEFORE_LENGHT", 0);
define("TRUNC_AFTER_LENGHT", 1);

function str_truncate($str, $length, $rep=TRUNC_BEFORE_LENGHT)
{
    //adicionada em 27/06/2006 para corrigir um bug
    if(strlen($str)<=$length) return $str;

    if($rep == TRUNC_BEFORE_LENGHT) $oc = strrpos(substr($str,0,$length),' ');
    if($rep == TRUNC_AFTER_LENGHT)    $oc = strpos(substr($str,$length),' ') + $length;

    return substr($str, 0, $oc);
}

function pr($str){
	echo "<div id=\"pr\">";
    echo "<p style=\"text-align:left;\"><a href=\"#\" onclick=\"document.getElementById('pr').style.display = 'none'\">[Fechar]</a></p>";
    echo "<pre style=\"font-family:verdana; font-size:10px;text-align:left\">";
	print_r($str);
	echo "</pre>";
    echo "</div>";
}

function estado_select($uf = ''){
		$estados = array(''  =>'',
						 'AC'=>'Acre',
						 'AL'=>'Alagoas',
						 'AP'=>'Amapa',
						 'AM'=>'Amazonas',
						 'BA'=>'Bahia',
						 'CE'=>'Ceara',
						 'DF'=>'Distrito Federal',
						 'ES'=>'Espirito Santo',
						 'GO'=>'Goias',
						 'MA'=>'Maranhao',
						 'MT'=>'Mato Grosso',
						 'MS'=>'Mato Grosso do Sul',
						 'MG'=>'Minas Gerais',
						 'PA'=>'Para',
						 'PB'=>'Paraiba',
						 'PR'=>'Parana',
						 'PE'=>'Pernambuco',
						 'PI'=>'Piaui',
						 'RJ'=>'Rio de Janeiro',
						 'RN'=>'Rio Grande do Norte',
						 'RS'=>'Rio Grande do Sul',
						 'RO'=>'Rondonia',
						 'RR'=>'Roraima',
						 'SC'=>'Santa Catarina',
						 'SP'=>'Sao Paulo',
						 'SE'=>'Sergipe',
						 'TO'=>'Tocantins'
						);

		foreach($estados as $chave => $valor){
			if ($uf == $chave){
				echo '<option value="'.$chave.'" selected="selected">'.$chave.'&nbsp;&nbsp;&nbsp;</option>';
			} else {
				echo '<option value="'.$chave.'">'.$chave.'&nbsp;&nbsp;&nbsp;</option>';
			}
		}
	}
	
	function estado_select_2($uf = ''){
		$estados = array(''  =>'Selecione',
						 'AC'=>'Acre',
						 'AL'=>'Alagoas',
						 'AP'=>'Amapa',
						 'AM'=>'Amazonas',
						 'BA'=>'Bahia',
						 'CE'=>'Ceara',
						 'DF'=>'Distrito Federal',
						 'ES'=>'Espirito Santo',
						 'GO'=>'Goias',
						 'MA'=>'Maranhao',
						 'MT'=>'Mato Grosso',
						 'MS'=>'Mato Grosso do Sul',
						 'MG'=>'Minas Gerais',
						 'PA'=>'Para',
						 'PB'=>'Paraiba',
						 'PR'=>'Parana',
						 'PE'=>'Pernambuco',
						 'PI'=>'Piaui',
						 'RJ'=>'Rio de Janeiro',
						 'RN'=>'Rio Grande do Norte',
						 'RS'=>'Rio Grande do Sul',
						 'RO'=>'Rondonia',
						 'RR'=>'Roraima',
						 'SC'=>'Santa Catarina',
						 'SP'=>'Sao Paulo',
						 'SE'=>'Sergipe',
						 'TO'=>'Tocantins'
						);

		foreach($estados as $chave => $valor){
			if ($uf == $chave){
				echo '<option value="'.$chave.'" selected="selected">'.$valor.'&nbsp;&nbsp;&nbsp;</option>';
			} else {
				echo '<option value="'.$chave.'">'.$valor.'&nbsp;&nbsp;&nbsp;</option>';
			}
		}
	}

function calcular_data($data_inicial, $parcela, $tipo){
    $data = explode("-", $data_inicial);
    $tempo = mktime(0, 0, 0, $data[1], $data[2], $data[0]);
	
    $i = 0;
    $parc = 0;

    while($parc < $parcela){
        $data_venc = date("Y-m-d", strtotime("+".$i." month", $tempo));
        $venc[$parc] = $data_venc;
        $i += $tipo;
        $parc++;
    }

    return $venc;
}

function log_sistema($msg_log){
  global $base;
  
  if (isset($_SESSION['user'])){
	$usuario    = normaltxt($_SESSION['user']['id_user']).' - '.$_SESSION['user']['nome'];
  } else {
	$usuario	= "Sistema";
  }
  $msg        = normaltxt($msg_log);
  $data_hora  = date("Y-m-d H:i:s");
  $ip         = $_SERVER['REMOTE_ADDR'];
  $ativo      = 'S';
  
  $sql_add_log = "INSERT INTO logs_sistema
                  (`usuario`, `msg`, `data_hora`, `ip`, `ativo`)
                  VALUES
                  ('$usuario', '$msg', '$data_hora', '$ip', '$ativo')
                  ";
  @$exe_add_log = mysql_query($sql_add_log, $base) or aw_error(mysql_error());
  if ($exe_add_log){
    return true;
  } else {
    return false;
  }
}

// funç&atilde;o que retorna o total de itens no carrinho de compras
function itens_no_carrinho(){
  global $base;
  
  $sessao = session_id();
  
  $sql_total = "SELECT COUNT(id) as total FROM carrinho WHERE sessao = '$sessao'";
  @$exe_total = mysql_query($sql_total, $base) or aw_error(mysql_error());
  if ($exe_total){
    $reg_total = mysql_fetch_array($exe_total, MYSQL_ASSOC);
    return $reg_total['total'];
  } else {
    return 0;
  }
}

// pegando as configurações do sistema
function get_info(){
  global $base;
  
  $sql_conf = "SELECT * FROM config WHERE id = 1";
  $exe_conf = mysql_query($sql_conf, $base) or aw_error(mysql_error());
  $num_conf = mysql_num_rows($exe_conf);
  if ($num_conf > 0){
    $reg_conf = mysql_fetch_array($exe_conf, MYSQL_ASSOC);
    return $reg_conf;
  }
}

function enviar_email_mudanca_status_pedido($email, $status){
  /*
  global $base;

  $infos = get_info();
  
  switch($status){
    case 'NOVO':
      $sql_html = "SELECT * FROM html_emails WHERE id = 1 AND ativo = 'S' LIMIT 0,1";
      break;
    case 'FATURADO':
      $sql_html = "SELECT * FROM html_emails WHERE id = 5 AND ativo = 'S' LIMIT 0,1";
      break;
    case 'AGUARDANDO_PAGAMENTO':
      $sql_html = "SELECT * FROM html_emails WHERE id = 4 AND ativo = 'S' LIMIT 0,1";
      break;
    case 'PAGO':
      $sql_html = "SELECT * FROM html_emails WHERE id = 2 AND ativo = 'S' LIMIT 0,1";
      break;
    case 'CANCELADO':
      $sql_html = "SELECT * FROM html_emails WHERE id = 3 AND ativo = 'S' LIMIT 0,1";
      break;
  }

  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= unhtmlentities(stripslashes($reg_html['html']));
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes($reg_html['txt']);
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($email,'');
						
	if($mail->Send()){
		$mail->ClearAddresses();
	} else {
		echo $mail->ErrorInfo;	
	}
  }
  */
}

function enviar_email_de_cobranca($dados_pedido){
  global $base;

  $infos = get_info();
  
  $sql_html = "SELECT * FROM html_emails WHERE id = 6 AND ativo = 'S' LIMIT 0,1";
  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= unhtmlentities(stripslashes($reg_html['html']));
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes($reg_html['txt']);
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($dados_pedido['email'],'');
						
	$mail->Send();
	$mail->ClearAddresses();
    
  }
}

function envia_email_recupera_senha($email, $link){
  global $base;

  $infos = get_info();
  
  $sql_html = "SELECT * FROM html_emails WHERE id = 7 AND ativo = 'S' LIMIT 0,1";
  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "painel/libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= unhtmlentities(stripslashes(preg_replace("/#LINK#/", $link, $reg_html['html'])));
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes(preg_replace("/#LINK#/", $link, $reg_html['txt']));
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($email,'');
						
	if($mail->Send()){
		$mail->ClearAddresses();
		return true;
	} else {
		return false;
	}
	
    
  }
}

function enviar_email_nova_senha($email, $senha){
  global $base;

  $infos = get_info();
  
  $sql_html = "SELECT * FROM html_emails WHERE id = 8 AND ativo = 'S' LIMIT 0,1";
  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "painel/libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
	
	$patterns[0] = '/#NOVA_SENHA#/';
	$patterns[1] = '/#EMAIL#/';
	$replacements[1] = $senha;
	$replacements[0] = $email;
	
	$mensagem_email	   = unhtmlentities(stripslashes(preg_replace($patterns, $replacements, $reg_html['html'])));
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= $mensagem_email;
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes(preg_replace($patterns, $replacements, $reg_html['txt']));
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($email,'');
						
	if($mail->Send()){
		$mail->ClearAddresses();
		return true;
	} else {
		return false;
	}
	
    
  }	
}

function enviar_email_lanhouse_ativa($d){
  
  global $base;

  $infos = get_info();
  
  $sql_html = "SELECT * FROM html_emails WHERE id = 9 AND ativo = 'S' LIMIT 0,1";
  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
	
	$patterns[0] = '/#NOME#/';
	$patterns[1] = '/#EMAIL#/';
	$replacements[1] = $d['nome_fantasia'];
	$replacements[0] = $d['email'];
	
	$mensagem_email	   = unhtmlentities(stripslashes(preg_replace($patterns, $replacements, $reg_html['html'])));
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= $mensagem_email;
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes(preg_replace($patterns, $replacements, $reg_html['txt']));
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($d['email'],'');
						
	if($mail->Send()){
		$mail->ClearAddresses();
		return true;
	} else {
		return false;
	}
	
    
  }
}

function enviar_email_comissao_paga($d){
  global $base;
  /*	
  $infos = get_info();
  
  $sql_html = "SELECT * FROM html_emails WHERE id = 10 AND ativo = 'S' LIMIT 0,1";
  $exe_html = mysql_query($sql_html, $base) or aw_error(mysql_error());
  $num_html = mysql_num_rows($exe_html);
  if ($num_html > 0){
    
    $reg_html = mysql_fetch_array($exe_html, MYSQL_ASSOC);
    
    // envia o e-mail
	include "libs/phpmailer/class.phpmailer.php";
	
    
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	if ($infos['ssl_ativo'] == 'S'){
      $mail->SMTPSecure = "ssl";
	}
    
	$mail->Host        = $infos['servidor_smtp'];
	$mail->Port        = $infos['porta'];
	$mail->Username    = $infos['email'];
	$mail->Password    = $infos['senha'];
	$mail->From        = $infos['email'];
    
    
	$mail->FromName    = unhtmlentities(stripslashes($infos['nome_empresa']));
	$mail->Subject     = unhtmlentities(stripslashes($reg_html['nome']));
	$mail->ContentType = "text/html";
						
	$html_mensagem	   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                          <html xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
						  <title></title>
						  </head>
						  <body>';
    $html_mensagem    .= unhtmlentities(stripslashes($reg_html['html']));
    $html_mensagem    .= '</body></html>';
	
    				
	$mail->Body		   = $html_mensagem;
	$mail->AltBody     = stripslashes($reg_html['txt']);
	$mail->WordWrap    = 50; // set word wrap
    
	$mail->AddAddress($d['email'],'');
						
	if($mail->Send()){
		$mail->ClearAddresses();
		return true;
	} else {
		return false;
	}
	
    
  }
  */
}

function sendmail($para, $config, $assunto, $html, $txt){
  global $base;
  
  $sql_ad_msg = "INSERT INTO sendmail (para, config, assunto, html, txt, status_envio, dh_cadastro, dh_envio)
				 VALUES ('$para', '".serialize($config)."', '$assunto', '$html', '$txt', 'N', '".date("Y-m-d H:i:s")."', NULL)";
  $exe_ad_msg = mysql_query($sql_ad_msg, $base) or aw_error(mysql_error());
}


// funç&atilde;o que verifica o saldo caso a compra tenha sido faturada
function verificar_saldo($d){
  global $base;
  
  // se for lan house
  if ($d['tipo'] == 3){ // lan house
    // verificando os pedidos que est&atilde;o como status FATURADO
    $sql_ped = "SELECT SUM(total_pedido) AS total_pedidos_faturado
                FROM pedidos
                WHERE
                id_user = '".$d['id_user']."' AND
                status_pedido = 'FATURADO'";
    $exe_ped = mysql_query($sql_ped, $base) or aw_error(mysql_error());
    $reg_ped = mysql_fetch_array($exe_ped, MYSQL_ASSOC);
    
    return $reg_ped['total_pedidos_faturado'];
  
  } elseif ($d['tipo'] == 4){ // funcionario
    // verificando os pedidos que est&atilde;o como status FATURADO
    $sql_ped = "SELECT SUM(total_pedido) AS total_pedidos_faturado
                FROM pedidos
                WHERE
                id_user = '".$d['id_relacionado']."' AND
                status_pedido = 'FATURADO'";
    $exe_ped = mysql_query($sql_ped, $base) or aw_error(mysql_error());
    $reg_ped = mysql_fetch_array($exe_ped, MYSQL_ASSOC);
    
    return $reg_ped['total_pedidos_faturado'];
  }
}

// funç&atilde;o que verifica o credito inicial
// se for lan house pega credito inicial do proprio id
// se for funcionario pega credito do id relacionado
function credito_inicial($d){
  global $base;
  
  // se for lan house
  if ($d['tipo'] == 3){ // lan house

    $sql_cred = "SELECT credito_inicial
                FROM aw_user
                WHERE
                id_user = '".$d['id_user']."'";
    $exe_cred = mysql_query($sql_cred, $base) or aw_error(mysql_error());
    $num_cred = mysql_num_rows($exe_cred);
    if ($num_cred > 0){
      $reg_cred = mysql_fetch_array($exe_cred, MYSQL_ASSOC);
      return $reg_cred['credito_inicial'];
    } else {
      return 0;
    }
    
  } elseif ($d['tipo'] == 4){ // funcionario

    $sql_cred = "SELECT credito_inicial
                FROM aw_user
                WHERE
                id_user = '".$d['id_relacionado']."'";
    $exe_cred = mysql_query($sql_cred, $base) or aw_error(mysql_error());
    $num_cred = mysql_num_rows($exe_cred);
    if ($num_cred > 0){
      $reg_cred = mysql_fetch_array($exe_cred, MYSQL_ASSOC);
      return $reg_cred['credito_inicial'];
    } else {
      return 0;
    }
    
  }
}

// funç&atilde;o que verifica se tem algum pedido faturado para o usuario lan house
// ou para o funcionario (neste caso verifica atraves do id relacionado)
function verificar_faturados_vencidos($d){
  global $base;
  
  // se for lan house
  if ($d['tipo'] == 3){ // lan house
    // verificando os pedidos que est&atilde;o com status FATURADO e vencidos
    $sql_ped = "SELECT COUNT(id_pedido) AS tem_pedido_vencido
                FROM pedidos
                WHERE
                id_user = '".$d['id_user']."' AND
                status_pedido = 'FATURADO' AND
                vencimento_fatura < '".date('Y-m-d')."'
                ";

    $exe_ped = mysql_query($sql_ped, $base) or aw_error(mysql_error());
    $reg_ped = mysql_fetch_array($exe_ped, MYSQL_ASSOC);
    if ($reg_ped['tem_pedido_vencido'] > 0){
      return true;
    }
  } elseif ($d['tipo'] == 4) { // funcionario
    // verificando os pedidos que est&atilde;o com status FATURADO e vencidos
    $sql_ped = "SELECT COUNT(id_pedido) AS tem_pedido_vencido
                FROM pedidos
                WHERE
                id_user = '".$d['id_relacionado']."' AND
                status_pedido = 'FATURADO' AND
                vencimento_fatura < '".date('Y-m-d')."'
                ";
    
    $exe_ped = mysql_query($sql_ped, $base) or aw_error(mysql_error());
    $reg_ped = mysql_fetch_array($exe_ped, MYSQL_ASSOC);
    if ($reg_ped['tem_pedido_vencido'] > 0){
      return true;
    }
  } else {
    return false;
  }
}

// funç&atilde;o que verifica a quantidade de pedidos faturados e vencidos
// retorna array com:
// 1 - número de pedidos faturados n&atilde;o pagos
// 2 - número de pedidos faturados vencidos
function pedidos_faturados($d){
  global $base;
  
  // se for lan house
  if ($d['tipo'] == 3){ // lan house
    
    // tem que pegar todos os funcionarios dessa lan house para saber os IDs deles e
    // juntar o o id da lan house e fazer o select dos pedidos.
    $sql_func = "SELECT id_user FROM aw_user WHERE id_relacionado = '".$d['id_user']."'";
    $exe_func = mysql_query($sql_func, $base) or aw_error(mysql_error());
    $num_func = mysql_num_rows($exe_func);
    $ar_func = array($d['id_user']);
    if ($num_func > 0){
      while ($reg_func = mysql_fetch_array($exe_func, MYSQL_ASSOC)){
        $ar_func[] = $reg_func['id_user'];
      }
    }
    
    $sql_aux = implode(" OR pedidos.id_user = ", $ar_func);
    
    // 1 - número de pedidos faturados n&atilde;o pagos
    $sql_ped1 = "SELECT COUNT(*) AS faturados FROM pedidos WHERE (pedidos.id_user = $sql_aux) AND status_pedido = 'FATURADO' AND vencimento_fatura >= '".date("Y-m-d")."'";
    $exe_ped1 = mysql_query($sql_ped1, $base) or aw_error(mysql_error());
    $reg_ped1 = mysql_fetch_array($exe_ped1, MYSQL_ASSOC);

    // 2 - número de pedidos faturados vencidos
    $sql_ped2 = "SELECT COUNT(*) AS vencidos FROM pedidos WHERE (pedidos.id_user = $sql_aux) AND status_pedido = 'FATURADO' AND vencimento_fatura < '".date("Y-m-d")."'";
    $exe_ped2 = mysql_query($sql_ped2, $base) or aw_error(mysql_error());
    $reg_ped2 = mysql_fetch_array($exe_ped2, MYSQL_ASSOC);
    
    $ar_retorno['faturados'] = $reg_ped1['faturados'];
    $ar_retorno['vencidos']  = $reg_ped2['vencidos'];
    
    return $ar_retorno;
    
  } elseif ($d['tipo'] == 4) { // funcionario
    
    // 1 - número de pedidos faturados n&atilde;o pagos
    $sql_ped1 = "SELECT COUNT(*) AS faturados FROM pedidos WHERE id_user = '".$d['id_user']."' AND status_pedido = 'FATURADO' AND vencimento_fatura >= '".date("Y-m-d")."'";
    $exe_ped1 = mysql_query($sql_ped1, $base) or aw_error(mysql_error());
    $reg_ped1 = mysql_fetch_array($exe_ped1, MYSQL_ASSOC);
    // 2 - número de pedidos faturados vencidos
    $sql_ped2 = "SELECT COUNT(*) AS vencidos FROM pedidos WHERE id_user = '".$d['id_user']."' AND status_pedido = 'FATURADO' AND vencimento_fatura < '".date("Y-m-d")."'";
    $exe_ped2 = mysql_query($sql_ped2, $base) or aw_error(mysql_error());
    $reg_ped2 = mysql_fetch_array($exe_ped2, MYSQL_ASSOC);
    
    $ar_retorno['faturados'] = $reg_ped1['faturados'];
    $ar_retorno['vencidos']  = $reg_ped2['vencidos'];
    
    return $ar_retorno;
    
  } else {
    
    $ar_retorno['faturados'] = 0;
    $ar_retorno['vencidos']  = 0;
    
    return $ar_retorno;
    
  }
  
}


/* gerenciar permissões */

function add_permissao($id_user, $blocos){
  global $base;
  
  if (is_array($blocos)){
    $sql_add = "INSERT INTO permissoes VALUES ";
    for ($i=0;$i<count($blocos);$i++){
      $sql_add_aux[] = "(NULL, '$id_user', '".$blocos[$i]."')";
    }
    $sql_adicionar_permissao = $sql_add." ".implode(", ", $sql_add_aux);
    $exe_adicionar_permissao = mysql_query($sql_adicionar_permissao, $base) or aw_error(mysql_error());
  } else {
    return false;
  }
  
}

function pegar_permissao($id_user){
  global $base;
  
  $sql_perm = "SELECT * FROM permissoes WHERE id_user = '$id_user'";
  $exe_perm = mysql_query($sql_perm, $base) or aw_error(mysql_error());
  $num_perm = mysql_num_rows($exe_perm);
  if ($num_perm > 0){
    while ($reg_perm = mysql_fetch_array($exe_perm, MYSQL_ASSOC)){
      $acesso[] = $reg_perm['bloco'];
    }
  }
  
  if (isset($acesso)){
    return $acesso;
  } else {
    return false;
  }
  
}

function deletar_todas_permissoes($id_user){
  global $base;
  
  $sql_del = "DELETE FROM permissoes WHERE id_user = '$id_user'";
  $exe_del = mysql_query($sql_del, $base) or aw_error(mysql_error());
  
}

function deletar_permissoes($id_user, $blocos){
  global $base;
  
  $sql_del_perm_aux = implode("' OR bloco = '", $blocos);
  
  $sql_del_perm = "DELETE FROM permissoes WHERE id_user = '$id_user' AND (bloco = '$sql_del_perm_aux')";
  $exe_del_perm = mysql_query($sql_del_perm, $base) or aw_error(mysql_error());
  
}

/* fim das funções para gerenciar permissões */

// funç&atilde;o que gera todos os pagamentos que devem ser pagos para os afiliadores,
// lan houses e funcionários
function gera_pagamentos_comissoes(){
  global $base;
	
  // quantidade de dias que possui o mês anterior
  $qtd_dias = date("t", strtotime("last Month"));
  // definimos a data inicial e final do mês anterior
  $data['ini'] 	= date("Y-m", strtotime("last Month"))."-01 00:00:00";
  $data['fim']	= date("Y-m", strtotime("last Month"))."-".$qtd_dias." 23:59:59";	
  /*
  # Caso queira forçar a rotina para um determinado mês.
  $data['ini']  = "2009-10-01 00:00:00";
  $data['fim']  = "2009-10-31 23:59:59";
  */
  // selecionando todos as comissões ainda n&atilde;o pagas de acordo com a data do mês passado
  $sql_pg = "SELECT SUM(valor) AS total, id_user FROM comissoes
			 WHERE
			 em_pagamento = 'N' AND
			 pago = 'N' AND
			 dh_cadastro BETWEEN '".$data['ini']."' AND '".$data['fim']."' GROUP BY id_user LIMIT 0,10";
  $exe_pg = mysql_query($sql_pg, $base) or aw_error(mysql_error());
  $num_pg = mysql_num_rows($exe_pg);
  if ($num_pg > 0){
	while ($reg_pg = mysql_fetch_array($exe_pg, MYSQL_ASSOC)){
	  $id_user			= $reg_pg['id_user'];
	  $valor 			= $reg_pg['total'];
	  $dh_cadastro 		= date("Y-m-d H:i:s");
	  $dh_pagamento  	= '0000-00-00 00:00:00';
	  $pago				= 'N';
	  $obs				= '';
	  
	  // adicionado o pagamento que deve ser feito para o usuário
	  $sql_add_pag = "INSERT INTO pagamentos
					  (id_user, valor, dh_cadastro, dh_pagamento, pago, obs)
					  VALUES
					  ('$id_user', '$valor', '$dh_cadastro', '$dh_pagamento', '$pago', '$obs')";
	  $exe_add_pag = mysql_query($sql_add_pag, $base) or aw_error(mysql_error());
	  // id do ultimo pagamento gerado
	  $id_pagamento = mysql_insert_id();
	  // se inseriu o pagamento corretamente ent&atilde;o damos update nas
	  // comissões da mesma data por usuário
	  if ($exe_add_pag){
		log_sistema("Gerou um pagamento para o usuário com ID = ".$id_user);
		// da tabela comissoes - comiss&atilde;o por comiss&atilde;o e nao a soma de todas
		$sql_alt_comissoes = "UPDATE comissoes SET em_pagamento = 'S', id_pagamento = '$id_pagamento' WHERE id_user = '$id_user' AND dh_cadastro BETWEEN '".$data['ini']."' AND '".$data['fim']."'";
		$exe_alt_comissoes = mysql_query($sql_alt_comissoes, $base) or aw_error(mysql_error());
		if ($exe_alt_comissoes){
		  log_sistema("Alterou para em pagamento as comissoes das datas ".$data['ini']." até ".$data['fim']." com pagamento ID = ".$id_pagamento);
		}
	  }
	}
  }
}



function tanahora(){
  $time_atual = time();
  $time_6_00  = mktime(18,0,0, date("m"), date("d"), date("Y"));
  //echo $time_6_00 - $time_atual;
  $porcentagem = 100 - ((($time_6_00 - $time_atual) * 100) / 36000);
  echo number_format($porcentagem, 2, ",", "");
}

function formata_telefone($telefone){
	
	$t1 = substr($telefone, 0, 2);
	$t2 = substr($telefone, 2, 4);
	$t3 = substr($telefone, 6, 2);
	$t4 = substr($telefone, 8, 2);
	
	return "(".$t1.") ".$t2." ".$t3." ".$t4;
}


function nome_jogo($idx){
	$ar_jogos = array(
					 'muclassic' => 'Mu Classic',
					 'muxpalta'  => 'Mu XP Alta', 
					 'eudemons'  => 'Eudemons'
					 );
	
	return $ar_jogos[$idx];
}

// funç&atilde;o que pega informações de um determinado usuário pela sess&atilde;o
// retorna um array()
function get_info_user($campos){
	global $base;
	
	$id_user = normaltxt($_SESSION['user']['id_user']);
	
	
	$sql_info = "SELECT $campos FROM aw_user WHERE id_user = '$id_user' LIMIT  0,1";
	$exe_info = mysql_query($sql_info, $base) or aw_error(mysql_error());
	$num_info = mysql_num_rows($exe_info);
	if ($num_info > 0){
		$reg_info = mysql_fetch_array($exe_info, MYSQL_ASSOC);
		return $reg_info;
	} else {
		return array();
	}
	
}

// funç&atilde;o que padroniza data no formato dd/mm/aaaa hh:mm:ss para o formato
// compativel com o mysql que é aaaa-mm-dd hh:mm:ss
function datetime_padrao_mysql($data){
  $d1 = explode(" ", $data);
  $d2 = explode("/", $d1[0]);
  $d3 = $d2[2]."-".$d2[1]."-".$d2[0]." ".$d1[1];
  return $d3;
}

// sem segundos
function datetime_padrao_mysql2($data){
  $d1 = explode(" ", $data);
  $d2 = explode("/", $d1[0]);
  $d3 = $d2[2]."-".$d2[1]."-".$d2[0]." ".$d1[1].":00";
  return $d3;
}

// funç&atilde;o que gerar uma fila de envio para enviar email com incentivo aos
// parceiros a comprar mais itens para revender
function gerar_fila_envio_email(){
	global $base;
	
	$sql_lista = "SELECT DISTINCT id_user FROM pedidos WHERE ('".time()."'  - dh_time) / 86400 >= 30 ORDER BY dh_time DESC";
	
	//echo $sql_lista;
	
}


// funç&atilde;o que gera log de compra de vips
function log_compra_vip($d){
	global $base_comunidade;
	if(conexao_comunidade()){
		$log_conta 			 = $d['log_conta'];
		$log_data  			 = time();
		$log_ip    			 = $_SERVER['REMOTE_ADDR'];
		$log_valor 			 = $d['log_valor'];
		$log_forma_pagamento = $d['log_forma_pagamento'];
		$log_jogo 			 = $d['log_jogo'];
		$log_vip			 = $d['log_vip'];
		
		$sql_log = "INSERT INTO log_compra_vip
					(log_conta, log_data, log_ip, log_valor, log_forma_pagamento, log_jogo, log_vip)
					VALUES
					('$log_conta', '$log_data', '$log_ip', '$log_valor', '$log_forma_pagamento', '$log_jogo', '$log_vip')";
		$exe_log = mysql_query($sql_log, $base_comunidade) or aw_error(mysql_error());
		mysql_close($base_comunidade);	
	} else {
		echo "Erro de conex&atilde;o com o banco de dados.";	
	}
}

function sanitize_title ($title)
	{
		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);
		$string = strtr ($title, $trans_tbl);
		
		$string = strtolower($string);
	
		// Código ASCII das vogais
		$ascii['a'] = range(224, 230);
		$ascii['e'] = range(232, 235);
		$ascii['i'] = range(236, 239);
		$ascii['o'] = array_merge(range(242, 246), array(240, 248));
		$ascii['u'] = range(249, 252);
	
		// Código ASCII dos outros caracteres
		$ascii['b'] = array(223);
		$ascii['c'] = array(231);
		$ascii['d'] = array(208);
		$ascii['n'] = array(241);
		$ascii['y'] = array(253, 255);
	
		foreach ($ascii as $key=>$item) {
			$acentos = '';
			foreach ($item AS $codigo) $acentos .= chr($codigo);
			$troca[$key] = '/['.$acentos.']/i';
		}
	
		$string = preg_replace(array_values($troca), array_keys($troca), $string);
	
		// Slug?									
		
		// Troca tudo que não for letra ou número por um caractere ($slug)
		$string = preg_replace('/[^a-z0-9]/i', "-", $string);
		// Tira os caracteres ($slug) repetidos
		$string = preg_replace('/' . "-" . '{2,}/i', "-", $string);
		$string = trim($string, "-");
		
	
		
		$string = str_replace("_", "-", $string);
		/*if (strlen($string) > $length) {
			$string = substr($string, 0, $length);
		}*/
	
		return $string;
	}




function paginate($curent_page, $total_pages, $paginate_format = array())
{

	$format = array(
		'start_tag'	=> ($paginate_format['start_tag'] != '')?$paginate_format['start_tag']:'',
		'close_tag'	=> ($paginate_format['close_tag'] != '')?$paginate_format['close_tag']:'',
		'a_open'	=> ($paginate_format['a_open'] != '')?$paginate_format['a_open']:'',
		'a_curent'	=> ($paginate_format['a_curent'] != '')?$paginate_format['a_curent']:'',
		'a_close'	=> ($paginate_format['a_close'] != '')?$paginate_format['a_close']:'',
		'url_q'		=> ($paginate_format['url_q'] != '')?$paginate_format['url_q']:'?p=%d',
		'lang_next'	=> ($paginate_format['lang_next'] != '')?$paginate_format['lang_next']:'Next',
		'lang_previous'	=> ($paginate_format['lang_previous'] != '')?$paginate_format['lang_previous']:'Previous',
		'a_space'	=> ($paginate_format['a_space'] != '')?$paginate_format['a_space']:'...',
		);

	$paginate = $format['start_tag'].'';

	if ($total_pages > 0)
	{
		//print previos buttton
		if ($curent_page > 1)
		{
			$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], ($curent_page-1)).'">'.$format['lang_previous'].'</a>'.$format['a_close'];
		}

		if ($total_pages > 12)
		{
			//digg style
			if ($curent_page < 9)
			{
				for ($i=1; $i<=10; $i++)
				{
					$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $i).'"'.(($i==$curent_page)?$paginate_format['a_curent']:'').'>'.$i.'</a>'.$format['a_close'];
				}
				$paginate .= $format['a_space'];
				$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], ($total_pages-1)).'">'.($total_pages-1).'</a>'.$format['a_close'];
				$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $total_pages).'">'.$total_pages.'</a>'.$format['a_close'];
			}
			else
			{
				$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], 1).'">1</a>'.$format['a_close'];
				$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], 2).'">2</a>'.$format['a_close'];
				$paginate .= $format['a_space'];

				if ($curent_page > ($total_pages-9))
				{
					for ($i=($total_pages-9); $i<=$total_pages; $i++)
					{
						$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $i).'"'.(($i==$curent_page)?$paginate_format['a_curent']:'').'>'.$i.'</a>'.$format['a_close'];
					}
				}
				else
				{
					for ($i=($curent_page-3); $i<=$curent_page+3; $i++)
					{
						$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $i).'"'.(($i==$curent_page)?$paginate_format['a_curent']:'').'>'.$i.'</a>'.$format['a_close'];
					}
					$paginate .= $format['a_space'];
					$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], ($total_pages-1)).'">'.($total_pages-1).'</a>'.$format['a_close'];
					$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $total_pages).'">'.$total_pages.'</a>'.$format['a_close'];

				}
			}
		}
		else
		{
			for ($i=1; $i<=$total_pages; $i++)
			{
				$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], $i).'"'.(($i==$curent_page)?$paginate_format['a_curent']:'').'>'.$i.'</a>'.$format['a_close'];
			}
		}
		//print next buttton
		if ($curent_page < $total_pages )
		{
			$paginate .= $format['a_open'].'<a href="'.sprintf($paginate_format['url_q'], ($curent_page+1)).'">'.$format['lang_next'].'</a>'.$format['a_close'];
		}
	}

	return $paginate.$format['close_tag'];
}













?>
