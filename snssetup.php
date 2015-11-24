<?php
session_start();
require 'vendor/autoload.php';
//create topic
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $rds->describeDBInstances(['DBInstanceIdentifier' => 'itmo-544-sukanya']);

//echo "No error as of now";

//print_r($result);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
//    print "============\n". $endpoint . "================";
//echo "endpoint is available";

$link = mysqli_connect($endpoint,"SukanyaN","SukanyaNDB","itmo544SNDB") or die("Error " . mysqli_error($link));

//print_r($link);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql1 = "SELECT topicarn,topicname FROM topic ";
$result = mysqli_query($link, $sql1);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	echo "topicarn:".$row["topicarn"]."<br>";
	if ($row["topicname"] == 'Mp2-S3Upload1')
	{
	echo "topic already exist";
	}
	else
	{
	$sns= new Aws\Sns\SnsClient([
	    'version' => 'latest',
	    'region'  => 'us-east-1'
	]);
	$topicName = 'Mp2-S3Upload1';
	$result = $sns->createTopic([
	    'Name' => $topicName, // REQUIRED
	]);

	$topicarn = $result['TopicArn'];
//	echo "topic arn value is ----------- $topicarn";
	//set topic attributes
	$result = $sns->setTopicAttributes([
	    'AttributeName' => 'DisplayName', // REQUIRED
	    'AttributeValue' => 'S3Upload',
	    'TopicArn' => $topicarn, // REQUIRED
	]);
	    $sql_insert = "INSERT INTO topic (topicarn,topicname) VALUES (?,?)";
	if (!($stmt = $link->prepare($sql_insert))) {
	    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}
	else
	{
	echo "statement topic was success";
	}

	$stmt->bind_param("ss",$topicarn,$topicName);
	if (!$stmt->execute()) {
	    print "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
//	printf("%d Row inserted.\n", $stmt->affected_rows);

	$stmt->close();
	}
    }
} 
else {
	$sns= new Aws\Sns\SnsClient([
	    'version' => 'latest',
	    'region'  => 'us-east-1'
	]);
	$topicName = 'Mp2-S3Upload1';
	$result = $sns->createTopic([
	    'Name' => $topicName, // REQUIRED
	]);

	$topicarn = $result['TopicArn'];
//	echo "topic arn value is ----------- $topicarn";
	//set topic attributes
	$result = $sns->setTopicAttributes([
	    'AttributeName' => 'DisplayName', // REQUIRED
	    'AttributeValue' => 'S3Upload',
	    'TopicArn' => $topicarn, // REQUIRED
	]);
	    $sql_insert = "INSERT INTO topic (topicarn,topicname) VALUES (?,?)";
	if (!($stmt = $link->prepare($sql_insert))) {
	    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}
	else
	{
	echo "statement topic was success";
	}

	$stmt->bind_param("ss",$topicarn,$topicName);
	if (!$stmt->execute()) {
	    print "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	printf("%d Row inserted.\n", $stmt->affected_rows);

	$stmt->close();
}

$link->close();

?>
