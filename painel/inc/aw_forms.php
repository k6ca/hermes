<?php
// Verificando se é include
$arq_bloco = 'aw_forms.php';
if (substr($_SERVER['PHP_SELF'],(strlen($arq_bloco)*-1)) == $arq_bloco){
	exit;
}

// array
/*
$array['name'] = "teste";
$array['id_form'] = "teste";
$array['size'] = 30;
$array['max_caractere'] = 10;
$array['value'] = '';
$array['id_img'] = 'f_data';
$array['tipo_data'] = '%d/%m/%Y';
$array['class'] = 'campo_form';
*/


// monta um input para colocar datas
function form_input_date($array){
	if(isset($array['disabled'])){
		$disabled = "disabled='disabled'";
	}
	echo '<input name="'.$array['name'].'" type="text" id="'.$array['id_form'].'" size="'.$array['size'].'" maxlength="'.$array['max_caractere'].'" value="'.$array['value'].'" class="campo_form"';
	if(isset($disabled)){
		echo $disabled;
	}
	echo '/>';
	if(!isset($disabled)){
		echo '&nbsp;<img src="js/jscalendar_1_0/img.gif" id="'.$array['id_img'].'" style="cursor: pointer; border: 1px solid red;" title="Selecione a Data"
		  onmouseover="this.style.background=\'red\';" onMouseOut="this.style.background=\'\'" />';
		
		echo '<script type="text/javascript">';
		echo 'Calendar.setup({
				inputField     :    "'.$array['id_form'].'",     // id of the input field
				ifFormat       :    "'.$array['tipo_data'].'",   // format of the input field
				button         :    "'.$array['id_img'].'",      // trigger for the calendar (button ID)
				align          :    "T1",                        // alignment (defaults to "Bl")
				singleClick    :    true
			  });
		</script>';
	}
}

/*
$lov['size_lov_id'] = 5;
$lov['nome_lov_id'] = 'id_lov_um';
$lov['nome_lov_desc'] = 'desc_lov_um';
$lov['size_lov_desc'] = 25;
$lov['coluna_busca'] = array('autor'=>'Autor','email'=>'E-Mail','nome_apl'=>'Nome');
$lov['tipo_busca'] = array(
							1=>'igual a',
							2=>'diferente de',
							3=>'inicia com',
							4=>'contém',
							5=>'termina com',
							6=>'maior que',
							7=>'menor que'
						);
$lov['qtd_pg'] = 14;
$lov['colunas'] = array('autor'=>'AUTOR','email'=>'E-MAIL');
$lov['colunas_size'] = array('48%','48%');
$lov['select'] = 'SELECT * FROM aw_apl';
$lov['where'] = '';
$lov['order'] = 'autor ASC';
$lov['sql_count'] = "SELECT COUNT(*) FROM aw_apl";
$lov['campo_id'] = 'id_apl';
$lov['campo_desc'] = 'autor';
$lov['sql_desc'] = "SELECT autor FROM aw_apl";
form_input_lov($lov);

*/

// monta uma lov
function form_input_lov($lov, $esp = false, $br = true){
	// serializando o array pra por na query string da pop up
	$lov_serializado = serialize($lov);
	// codificando a serialização
	$lov_codigicado  = base64_encode($lov_serializado);
	//colocando 2 espaços
	if ($esp == true){
		$esp = "&nbsp;&nbsp;";
	} elseif ($esp == 0) {
		$esp = "";
	}
	if ($br == true){
		$br = "<br>";
	} else {
		$br = "";
	}

	$html  = $br.$esp.'<input type="text" size="'.$lov['size_lov_id'].'" name="'.$lov['nome_lov_id'].'" value="'.$lov['v_id'].'" class="campo_log_id" readonly="readonly">';
	$html .= '<img src="img/lov.gif" alt="Abrir para selecionar" border="0" class="campo_lov_img" onclick="popupcenter(\'inc/aw_lov.php?q='.$lov_codigicado.'\',\'pg\',600,333,\'no\',\'no\')">';
	$html .= '<input type="text" name="'.$lov['nome_lov_desc'].'" size="'.$lov['size_lov_desc'].'" value="'.$lov['v_desc'].'" class="campo_log_desc" readonly="readonly">';
	echo $html;
}

// adicona recursos pro editor html
function add_editor(){
	include("js/FCKeditor/fckeditor.php") ;
	echo '<script type="text/javascript" src="js/FCKeditor/fckeditor.js"></script>';
}

/*
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Example content CSS (should be your site CSS)
	content_css : "css/example.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});



<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		
		theme_advanced_toolbar_location : "top",
		convert_urls : false,
		font_size_style_values : "xx-small,x-small,small,medium,large,x-large,xx-large"
	});
</script>
<!-- /TinyMCE -->
*/
function add_tinyMCE(){
	echo '<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "style,layer,table,advimage,advlink,preview,visualchars",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_buttons4 : "tablecontrols,forecolor,backcolor",
		convert_urls : false,
		font_size_style_values : "xx-small,x-small,small,medium,large,x-large,xx-large"
	});
</script>
<!-- /TinyMCE -->
';
}

function add_ajax(){
	echo '<script type="text/javascript" src="js/ajaxutil.js"></script>';
}
?>
