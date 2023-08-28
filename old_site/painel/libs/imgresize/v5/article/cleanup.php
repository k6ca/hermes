<?PHP
	$dh = opendir($folder);
	$time = time() - 300;
	while( $file = readdir($dh)){

		if($file >= "0" && $file <= "9" && $file < "$time "){
			unlink($folder.$file);
		}else{

		}
	}

?>