<?php
session_start();
require 'vendor/autoload.php';
if(!empty($_POST)){
echo "you are being subscribed to the number ";
echo  $_POST['phoneNo'];
$phone = $_POST['phoneNo'];
}
else
{
echo "Please enter Phone number in the format 1-200-000-0000";
}
//echo "sns start here";
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $rds->describeDBInstances(['DBInstanceIdentifier' => 'itmo-544-sukanya']);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"SukanyaN","SukanyaNDB","itmo544SNDB") or die("Error " . mysqli_error($link));

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$sql1 = "SELECT topicarn,topicname FROM topic ";
$result = mysqli_query($link, $sql1);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

	if ($row["topicname"] == 'Mp2-S3Upload1')
	{
	$sns= new Aws\Sns\SnsClient([
	    'version' => 'latest',
	    'region'  => 'us-east-1'
	]);
	//subscribe to the topic using the sms protocol
	$result = $sns->subscribe([
	    'Endpoint' => $phone,
	    'Protocol' => 'sms', // REQUIRED
	    'TopicArn' => $row["topicarn"], // REQUIRED
	]);
	}
	
    }
} 


echo "You will receive a Message to the mobile number provided.";
echo "Reply back to be subscribed to receive message ";

$subarn= $result['SubscriptionArn'];
echo "subscription arn is $subarn";

?>
<!DOCTYPE html>
 <meta charset="UTF-8"> 
<html>
<body>
<br>
<br>
<a href="index.php"> Main page</a>
</body>
</html> 



    
