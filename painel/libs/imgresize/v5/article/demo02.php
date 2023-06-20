<?PHP
	require("hft_image.php");
	$original_image	=	"img/picture.jpg";
error_reporting(E_ALL);
	$desired_width = $HTTP_GET_VARS["w"];
	$desired_height = $HTTP_GET_VARS["h"];
	
	//Check for the limits 
	//I made it to dont overload my server
	if(($desired_width<10)||($desired_width > 200))die("Bad width parameter. Must be 10..200 "); 
	if(($desired_height<10)||($desired_height > 200))die("Bad height parameter Must be 10..200 "); 

	$image	=	new hft_image($original_image);
	$image->resize($desired_width, $desired_height, '-');
	$image->output_resized("", "JPG");
?>