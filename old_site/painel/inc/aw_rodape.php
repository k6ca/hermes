<?php
/**
* Arquivo rodapé
*/
// Verificando se é include
$arq_bloco = 'aw_rodape.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}
?>
<div id="rodape">
	<div id="contador">
	<?php
	$contador = aw_contador(true);
	echo "Pageviews<br>Por Hora : ";
	echo $contador['por_hora'];
	echo "<br>";
	echo "Total : ";
	echo $contador['total'];
	?>	
	</div>
<p>
<?php
echo $config['sistema']." - <a href=\"http://".$config['site_cliente']."\">".$config['site_cliente']."</a><br>";
echo "Desenvolvido por <a href=\"http://".$config['site']."\">".$config['empresa']."</a>";
?>
</p>
</div>
</div>
</body>
</html>
