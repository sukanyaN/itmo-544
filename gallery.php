<?php
session_start();
require 'vendor/autoload.php';

#create RDSclient using the us-west-2 
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

#fetch the DB instance
$result = $rds->describeDBInstances(['DBInstanceIdentifier' => 'itmo-544-sukanya']);


#get the end point to the instance
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
  //  print "============\n". $endpoint . "================";
//echo "endpoint is available";

$link = mysqli_connect($endpoint,"SukanyaN","SukanyaNDB","itmo544SNDB");

?>

<!DOCTYPE html>
<html><head>
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="https://raw.githubusercontent.com/sukanyaN/itmo-544-final/master/css/magnific-popup.css">

<style>
.magnific-gallery
{
	list-style: none;
}

.magnific-gallery li
{
	float: left;
	height: 100px;
}

.magnific-gallery img
{
	height: 100%;
}

    </style>
</head>
<body>
<?php 
if (!$link)
{
die("connection failed". mysqli_connect_error());
}

else
{
if(isset($_POST['email'])){
$useremail = $_POST['email'];
$sqlstat= "SELECT * FROM items WHERE Email='$useremail'";
}
else
{
$sqlstat= "SELECT ID, JpgFileName, RawS3URL FROM items";
}

$result = mysqli_query($link, $sqlstat);

$imgLocations = array();
print "Result set order...\n";

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //this will append the path of images to an array
        $imgLocations[] = $row["RawS3URL"];
//        echo "id: " . $row["ID"]."- RawS3URL" . $row["RawS3URL"]. "<br>";
    }
} 
else {
    echo "----0 results";
}

$link->close();
}
?>
<ul class="magnific-gallery">
  <?php foreach ($imgLocations as $key => $value) {
  ?>
  <li>	
  <a href="<?php echo $value ?>"> <img src="<?php echo $value ?>"></img><?php echo $key ?></a>
  </li>
  <?php }?>
</ul>

</body>

<!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!-- Magnific Popup core JS file -->
<script src="https://raw.githubusercontent.com/sukanyaN/itmo-544-final/master/js/jquery.magnific-popup.js"></script>

<!-- js file on github and link -->
<script src="https://raw.githubusercontent.com/sukanyaN/itmo-544-final/master/js/jqgallery.js"></script>
</html>
