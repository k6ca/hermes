<?php
/**
* Arquivo que gerencia os blocos
*/

// Verificando se é include
$arq_bloco = 'aw_blocos.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}


?>

<td align="center" bgcolor="#FFFFFF" valign="top" height="460">

	<?php
	if ($num_blc > 0 && isset( $_SESSION['user'])){
		if (access_bloco($blc, $_SESSION['user']['acesso'])==true){
		  if ($reg_blc['layout'] == 'S'){
	?>
	<table width="99%" border="0" cellspacing="0" cellpadding="0" id="tb_mod">
		<tr>
			<td width="92%" class="top_mod_blc tit_bloco">&nbsp;<?php echo stripslashes($reg_blc['descricao'])?></td>
			<td width="8%" class="top_mod_blc">
			<?php
			if (isset($reg_blc)){
				if ($reg_blc['hab_btn'] == 'S'){
			?>
			  <div align="right">
				<!--
			  	<form action="#" method="post" name="f_janela" style="margin:0px; padding:0px">
			    <input type="button" class="btn_max" onclick="win_control('index.php?blc=<?php echo $blc?>&acao=<?php echo $_GET['acao']?>&tab=<?php echo $_SESSION['nav']['modulo']['tab']?>&max')" title="Maximizar Janela" />
			    <input type="button" class="btn_min" onclick="win_control('index.php?blc=<?php echo $blc?>&acao=<?php echo $_GET['acao']?>&tab=<?php echo $_SESSION['nav']['modulo']['tab']?>&min')" title="Minimizar Janela" />
				<?php
				if (isset($_POST['registro'])){
				?>
				<input type="hidden" name="registro" value="<?php echo $_POST['registro']?>" />
				<?php
				}
				?>
				</form>
				-->
			  </div>
			<?php
				}
			}  
			?>
			  </td>
		</tr>
		<tr>
			<td colspan="2">
		<!-- Início de um bloco -->
		<?php
		  }
			if (file_exists('inc/blc/'.$blc.'.php')){
				require 'inc/blc/'.$blc.'.php';
			} else {
				echo 'nao existe';
			}
		  if ($reg_blc['layout'] == 'S'){
		?>
		<!-- Final de um bloco -->
			</td>
		</tr>
	</table>
	<?php
		  }
		} else { // fecha if do access_bloco()
			bloco_branco();
		}
	} else { // fecha if se retornou alguma coisa do bloco
		bloco_branco();
	}
	
	?>
</td>
</table>