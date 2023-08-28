<?php
// Verificando se é include
$arq_bloco = 'aw_senha.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

$_GET['acao'] = 'Alterar';

if (isset($_GET['acao']) && $_GET['acao'] == 'Alterar'){
	// validando informações e cadastrando caso esteja correto.
	if (isset($_POST['form_submit'])){
		// pass_atual
		if (isset($_POST['pass_atual'])){
			if (empty($_POST['pass_atual'])){
				$erro[] = 'Informe sua senha atual.';
			}
		} else {
			$erro[] = 'Informe sua senha atual.';
		}
		// new_pass
		if (isset($_POST['new_pass'])){
			if (empty($_POST['new_pass'])){
				$erro[] = "Informe sua nova senha.";
			} else {
				if (strlen($_POST['new_pass']) > 20){
					$erro[] = 'Nova senha com no máximo 20 caracteres.';
				}
			}
		} else {
			$erro[] = "Informe sua nova senha.";
		}
		// comprando new_pass com a confirmação re_new_pass
		if (!isset($erro)){
			if ($_POST['new_pass'] != $_POST['re_new_pass']){
				$erro[] = "Senha redigitada não é igual a nova senha.";
			}
		}
		
		if (!isset($erro)){
			// verifica se a senha atual corresponde com a senha do usuário
			// que está logado no momento.
			$sql_senha = "SELECT count(*) as tem_user FROM aw_user WHERE email = '".normaltxt($_SESSION['user']['email'])."' AND senha = PASSWORD('".normaltxt($_POST['pass_atual'],true)."')";
			$exe_senha = mysql_query($sql_senha, $base) or aw_error(mysql_error());
			$reg_senha = mysql_fetch_array($exe_senha, MYSQL_ASSOC);
			if ($reg_senha['tem_user'] <= 0){
				$erro[] = "Senha atual incorreta, tente novamente.";
			}	
		}
		
		
		// tudo ok ?
		if (!isset($erro)){
			$nova_senha = normaltxt($_POST['new_pass'],true);

			$sql_troca_senha = "UPDATE aw_user SET 
					    senha 	= PASSWORD('".$nova_senha."') 
					    WHERE
					    id_user 	= '".$_SESSION['user']['id_user']."' AND 
					    email 	= '".normaltxt($_SESSION['user']['email'])."' AND
					    senha 	= PASSWORD('".normaltxt($_POST['pass_atual'],true)."')";
			
			$exe_troca_senha = mysql_query($sql_troca_senha, $base) or aw_error(mysql_error());
			$sucesso[] = "Senha alterada com sucesso.";
			log_sistema("Alterou sua senha.");
			unset($_POST);
		}
	}
?>
<form name="frm_incluir" action="<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=Alterar" method="post" onSubmit="return performCheck('frm_incluir', rules, 'classic');">
		<input type="hidden" name="form_submit" value="1" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
        <td colspan="3" class="grid_topo" height="20">&nbsp;<b><?php echo $_GET['acao']?></b></td>
        </tr>
		<?php
		if (isset($sucesso)){
		   ?>
		<tr>
            <td colspan="3" class="bg_incluir">
            <?
			sucesso_bloco($sucesso);
			?>
            </td>
        </tr>
            <?
		}
		
		if (isset($erro) && count($erro) > 0){
            ?>
        <tr>
            <td colspan="3" class="bg_incluir">
            <?
			erro_bloco($erro);
            ?>
            </td>
        </tr>
            <?
		}
		?>
		<tr>
        <td colspan="3" class="bg_incluir">
          <ul id="info_sistema">
            <li>Os campos marcados com * são de preenchimento obrigatório.</li>
          </ul>
        </td>
        </tr>
		<tr>
		<td width="34%" class="bg_incluir">&nbsp;&nbsp;Senha atual *<br />
		&nbsp;&nbsp;<input name="pass_atual" type="password" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['pass_atual'])?>" /></td>
    	<td width="33%" class="bg_incluir">Nova senha *<br />
		<input name="new_pass" type="password" class="campo_form" size="30" maxlength="200" value="<?php echo @stripslashes($_POST['new_pass'])?>" />    	</td>
    	<td width="33%" class="bg_incluir">Redigitar nova senha *<br />
		<input name="re_new_pass" type="password" class="campo_form" size="30" maxlength="20" value="<?php echo @stripslashes($_POST['re_new_pass'])?>" /></td>
  		</tr>
		<tr>
          <td width="34%" class="bg_incluir_p">&nbsp;</td>
          <td width="33%" class="bg_incluir_p">&nbsp;</td>
		  <td width="33%" class="bg_incluir_p">&nbsp;</td>
       </tr>
        <tr>
        <td colspan="3" class="grid_topo" height="30" align="center">
		<input type="submit" value="   OK   " class="campo_grid" />
		<input type="button" value=" FECHAR " class="campo_grid" onClick="location.href='<?php echo $_SERVER['PHP_SELF']?>?blc=<?php echo $blc?>&acao=list'" />		</td>
        </tr>
        </table>
		</form>
		<script type="text/javascript">
			function pass_iguais(){
				var new_pass    = document.frm_incluir.new_pass.value;
				var re_new_pass = document.frm_incluir.re_new_pass.value;
				if (new_pass != re_new_pass){
					return 'Senha redigitada não é igual a nova senha.';
				} else {
					return null;
				}
			}
			
			var rules = new Array();
			rules[0] = 'pass_atual|required|Coloque a senha antiga.';
			rules[1] = 'new_pass|required|Coloque a nova senha.';
			rules[2] = 'new_pass|maxlengt|20|Nova senha com no máximo 20 caracteres.';
			rules[3] = 're_new_pass|required|Redigite a nova senha.';
			rules[4] = 'pass_iguais()|custom';
		</script>
<?php	
}
?>