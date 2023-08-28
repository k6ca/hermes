<b>Warning: your image files will exist on the server for 5 min. Then they will be removed. 
<br>I must do this to prevent server overflow.</b><hr>
<?PHP
	require("hft_image.php");
	$folder = "img/";
	$original_image	=	$folder."picture.jpg";
	
	$destination_width	=	120;
	$destination_height	=	120;

	$image	=	new hft_image($original_image);
	
	echo("Original image:<br>");
	echo("<img src='$original_image' ><br>");
	$sz=getimagesize($original_image);
	echo("Width=$image->image_original_width Height=$image->image_original_height<br>");
	echo("Stored as: $original_image<br>");

	$image->resize($destination_width, $destination_height, '-');
	$new_file = $folder.time()."minus_00.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize($destination_width, $destination_height, '-') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize($destination_height, "*", '-');
	$new_file = $folder.time()."minus_01.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize($destination_height, '*', '-') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize("*", $destination_height, '-');
	$new_file = $folder.time()."minus_02.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize('*', $destination_height, '-') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize($destination_height, $destination_height, '+');
	$new_file = $folder.time()."plus_00.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize($destination_height, $destination_height, '+') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize($destination_height, $destination_height, '0');
	$new_file = $folder.time()."null_00.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize($destination_height, $destination_height, '0') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize($destination_height, '*', '0');
	$new_file = $folder.time()."null_01.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize($destination_height, '*', '0') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

	$image->resize('*', $destination_width, '0');
	$new_file = $folder.time()."null_02.jpg";
	$image->output_resized($new_file, "JPEG");
	echo("<hr>Resized with resize('*', $destination_width, '0') <br>");
	echo("<img src='$new_file' ><br>");
	
	echo("New width=$image->image_resized_width New height=$image->image_resized_height<br>");
	echo("Stored as: $new_file");

require("cleanup.php"); // just for demo purposes. this script will remove all temporary imaegs older than X minutes
?>