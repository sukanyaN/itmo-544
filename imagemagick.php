<?php
session_start();
require 'vendor/autoload.php';
header('Content-type: image/jpeg');
echo "Submit.php page";
if(!empty($_POST)){
echo $_POST['phone'];
}
else {
echo "Post data is empty";
}
$image = new Imagick($_FILES['userfile']);
$image->thumbnailImage(100, 0);

echo $image;

?>
